<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user();
        
        // Get enrolled courses
        $enrolledCourseIds = $student->enrollments()->pluck('course_id');
        
        $query = Assignment::with(['course', 'teacher'])
            ->whereIn('course_id', $enrolledCourseIds)
            ->published();

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->whereDoesntHave('submissions', function ($q) use ($student) {
                    $q->where('student_id', $student->id)
                      ->where('status', '!=', 'draft');
                });
            } elseif ($request->status === 'submitted') {
                $query->whereHas('submissions', function ($q) use ($student) {
                    $q->where('student_id', $student->id)
                      ->where('status', '!=', 'draft');
                });
            }
        }

        $assignments = $query->latest('due_date')->paginate(15);
        $courses = Course::whereIn('id', $enrolledCourseIds)->get();

        // Mark all graded submissions as viewed when student visits assignments page
        \App\Models\Submission::where('student_id', $student->id)
            ->whereIn('status', ['graded', 'returned'])
            ->whereNotNull('score')
            ->whereNull('viewed_at')
            ->update(['viewed_at' => now()]);

        return view('student.assignments.index', compact('assignments', 'courses'));
    }

    public function show(Assignment $assignment)
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

        $assignment->load(['course', 'teacher']);
        
        // Get or create submission
        $submission = $assignment->submissions()
            ->where('student_id', $student->id)
            ->with('files')
            ->first();

        // Mark submission as viewed if it's graded and hasn't been viewed yet
        if ($submission && $submission->score !== null && $submission->viewed_at === null) {
            $submission->viewed_at = now();
            $submission->save();
        }

        return view('student.assignments.show', compact('assignment', 'submission'));
    }
}
