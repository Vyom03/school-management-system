<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use App\Models\SubmissionFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function store(Request $request, Assignment $assignment)
    {
        $student = auth()->user();

        // Verify student is enrolled in the course
        $isEnrolled = $student->enrollments()
            ->where('course_id', $assignment->course_id)
            ->exists();

        if (!$isEnrolled) {
            abort(403, 'Unauthorized');
        }

        // Check if assignment is published
        if (!$assignment->is_published) {
            abort(404, 'Assignment not found');
        }

        $validated = $request->validate([
            'content' => 'nullable|string',
            'files' => 'nullable|array|max:10',
            'files.*' => 'file|max:10240', // 10MB max per file
            'submit' => 'boolean', // If true, mark as submitted
        ]);

        // Validate file types if specified
        if ($assignment->allowed_file_types && $request->hasFile('files')) {
            $allowedTypes = array_map('trim', explode(',', $assignment->allowed_file_types));
            foreach ($request->file('files') as $file) {
                $extension = strtolower($file->getClientOriginalExtension());
                if (!in_array($extension, $allowedTypes)) {
                    return back()->withErrors([
                        'files' => "File type '{$extension}' is not allowed. Allowed types: " . $assignment->allowed_file_types
                    ]);
                }
            }
        }

        // Validate file sizes if specified
        if ($assignment->max_file_size && $request->hasFile('files')) {
            $maxSizeBytes = $assignment->max_file_size * 1024; // Convert KB to bytes
            foreach ($request->file('files') as $file) {
                if ($file->getSize() > $maxSizeBytes) {
                    return back()->withErrors([
                        'files' => "File '{$file->getClientOriginalName()}' exceeds maximum size of " . $assignment->max_file_size . " KB"
                    ]);
                }
            }
        }

        // Get or create submission
        $submission = Submission::firstOrCreate(
            [
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
            ],
            [
                'status' => 'draft',
            ]
        );

        // Update content
        if ($request->filled('content')) {
            $submission->content = $validated['content'];
        }

        // Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('submissions', 'public');
                
                SubmissionFile::create([
                    'submission_id' => $submission->id,
                    'file_path' => $path,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        // If submit button was clicked, mark as submitted
        if ($request->has('submit')) {
            $submission->status = 'submitted';
            $submission->submitted_at = now();
        }

        $submission->save();

        if ($request->has('submit')) {
            return redirect()->route('student.assignments.show', $assignment)
                ->with('success', 'Assignment submitted successfully!');
        }

        return redirect()->route('student.assignments.show', $assignment)
            ->with('success', 'Draft saved successfully!');
    }

    public function update(Request $request, Submission $submission)
    {
        $student = auth()->user();

        // Verify student owns this submission
        if ($submission->student_id !== $student->id) {
            abort(403, 'Unauthorized');
        }

        // Cannot update if already graded
        if (in_array($submission->status, ['graded', 'returned'])) {
            return back()->withErrors(['error' => 'Cannot update a graded submission']);
        }

        $validated = $request->validate([
            'content' => 'nullable|string',
            'files' => 'nullable|array|max:10',
            'files.*' => 'file|max:10240', // 10MB max per file
            'submit' => 'boolean',
            'delete_files' => 'nullable|array',
        ]);

        // Handle file deletion
        if ($request->filled('delete_files')) {
            $filesToDelete = SubmissionFile::whereIn('id', $request->delete_files)
                ->where('submission_id', $submission->id)
                ->get();

            foreach ($filesToDelete as $file) {
                if (Storage::disk('public')->exists($file->file_path)) {
                    Storage::disk('public')->delete($file->file_path);
                }
                $file->delete();
            }
        }

        // Update content
        if ($request->filled('content')) {
            $submission->content = $validated['content'];
        }

        // Handle new file uploads
        if ($request->hasFile('files')) {
            $assignment = $submission->assignment;

            // Validate file types if specified
            if ($assignment->allowed_file_types) {
                $allowedTypes = array_map('trim', explode(',', $assignment->allowed_file_types));
                foreach ($request->file('files') as $file) {
                    $extension = strtolower($file->getClientOriginalExtension());
                    if (!in_array($extension, $allowedTypes)) {
                        return back()->withErrors([
                            'files' => "File type '{$extension}' is not allowed. Allowed types: " . $assignment->allowed_file_types
                        ]);
                    }
                }
            }

            // Validate file sizes if specified
            if ($assignment->max_file_size) {
                $maxSizeBytes = $assignment->max_file_size * 1024;
                foreach ($request->file('files') as $file) {
                    if ($file->getSize() > $maxSizeBytes) {
                        return back()->withErrors([
                            'files' => "File '{$file->getClientOriginalName()}' exceeds maximum size of " . $assignment->max_file_size . " KB"
                        ]);
                    }
                }
            }

            foreach ($request->file('files') as $file) {
                $path = $file->store('submissions', 'public');
                
                SubmissionFile::create([
                    'submission_id' => $submission->id,
                    'file_path' => $path,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        // If submit button was clicked, mark as submitted
        if ($request->has('submit')) {
            $submission->status = 'submitted';
            if (!$submission->submitted_at) {
                $submission->submitted_at = now();
            }
        }

        $submission->save();

        if ($request->has('submit')) {
            return redirect()->route('student.assignments.show', $submission->assignment)
                ->with('success', 'Assignment submitted successfully!');
        }

        return redirect()->route('student.assignments.show', $submission->assignment)
            ->with('success', 'Draft saved successfully!');
    }

    public function grade(Request $request, Submission $submission)
    {
        // Only teachers and admins can grade
        if (!auth()->user()->hasAnyRole(['teacher', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        // Verify grader has permission (teacher must own the assignment, or admin)
        if (auth()->user()->hasRole('teacher')) {
            if ($submission->assignment->teacher_id !== auth()->id()) {
                abort(403, 'Unauthorized');
            }
        }

        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:' . $submission->assignment->max_score,
            'feedback' => 'nullable|string',
        ]);

        $submission->score = $validated['score'];
        $submission->feedback = $validated['feedback'] ?? null;
        $submission->status = 'graded';
        $submission->graded_at = now();
        $submission->graded_by = auth()->id();
        $submission->viewed_at = null; // Reset viewed status when grade is updated
        $submission->save();

        // Create or update corresponding Grade record for gradebook integration
        $enrollment = \App\Models\Enrollment::where('student_id', $submission->student_id)
            ->where('course_id', $submission->assignment->course_id)
            ->first();

        if ($enrollment) {
            // Check if grade already exists for this assignment
            $existingGrade = \App\Models\Grade::where('enrollment_id', $enrollment->id)
                ->where('assignment_name', $submission->assignment->title)
                ->first();

            if ($existingGrade) {
                // Update existing grade
                $existingGrade->score = $validated['score'];
                $existingGrade->max_score = $submission->assignment->max_score;
                $existingGrade->comments = $validated['feedback'] ?? $existingGrade->comments;
                $existingGrade->graded_by = auth()->id();
                $existingGrade->save();
            } else {
                // Create new grade
                \App\Models\Grade::create([
                    'enrollment_id' => $enrollment->id,
                    'assignment_name' => $submission->assignment->title,
                    'score' => $validated['score'],
                    'max_score' => $submission->assignment->max_score,
                    'comments' => $validated['feedback'] ?? null,
                    'graded_by' => auth()->id(),
                ]);
            }
        }

        return back()->with('success', 'Submission graded successfully!');
    }

    public function downloadFile(SubmissionFile $file)
    {
        // Verify user has access (student, teacher, or admin)
        $user = auth()->user();
        $submission = $file->submission;
        $assignment = $submission->assignment;

        $hasAccess = false;

        // Student can download their own files
        if ($submission->student_id === $user->id) {
            $hasAccess = true;
        }

        // Teacher can download if they own the assignment
        if ($user->hasRole('teacher') && $assignment->teacher_id === $user->id) {
            $hasAccess = true;
        }

        // Admin can always download
        if ($user->hasRole('admin')) {
            $hasAccess = true;
        }

        if (!$hasAccess) {
            abort(403, 'Unauthorized');
        }

        if (!Storage::disk('public')->exists($file->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($file->file_path, $file->original_filename);
    }
}
