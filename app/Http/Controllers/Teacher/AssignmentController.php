<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $teacher = auth()->user();
        
        $query = Assignment::with(['course', 'submissions'])
            ->where('teacher_id', $teacher->id);

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $assignments = $query->latest()->paginate(15);
        $courses = Course::where('teacher_id', $teacher->id)->get();

        return view('teacher.assignments.index', compact('assignments', 'courses'));
    }

    public function create()
    {
        $teacher = auth()->user();
        $courses = Course::where('teacher_id', $teacher->id)->get();
        return view('teacher.assignments.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $teacher = auth()->user();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'due_date' => 'required|date',
            'due_time' => 'nullable|date_format:H:i',
            'max_score' => 'required|numeric|min:0',
            'allowed_file_types' => 'nullable|string',
            'max_file_size' => 'nullable|integer|min:1',
            'is_published' => 'boolean',
        ]);

        // Verify teacher owns the course
        $course = Course::findOrFail($validated['course_id']);
        if ($course->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized');
        }

        // Combine date and time
        if ($request->filled('due_time')) {
            $validated['due_date'] = $validated['due_date'] . ' ' . $request->due_time;
        }

        $validated['teacher_id'] = $teacher->id;
        $validated['is_published'] = $request->has('is_published');

        Assignment::create($validated);

        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment created successfully!');
    }

    public function show(Assignment $assignment)
    {
        $teacher = auth()->user();
        
        // Verify teacher owns this assignment
        if ($assignment->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized');
        }

        $assignment->load(['course', 'submissions.student', 'submissions.files']);
        
        $submissions = $assignment->submissions()
            ->with(['student', 'files'])
            ->latest()
            ->get();

        return view('teacher.assignments.show', compact('assignment', 'submissions'));
    }

    public function edit(Assignment $assignment)
    {
        $teacher = auth()->user();
        
        // Verify teacher owns this assignment
        if ($assignment->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized');
        }

        $courses = Course::where('teacher_id', $teacher->id)->get();
        return view('teacher.assignments.edit', compact('assignment', 'courses'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $teacher = auth()->user();
        
        // Verify teacher owns this assignment
        if ($assignment->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'due_date' => 'required|date',
            'due_time' => 'nullable|date_format:H:i',
            'max_score' => 'required|numeric|min:0',
            'allowed_file_types' => 'nullable|string',
            'max_file_size' => 'nullable|integer|min:1',
            'is_published' => 'boolean',
        ]);

        // Verify teacher owns the course
        $course = Course::findOrFail($validated['course_id']);
        if ($course->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized');
        }

        // Combine date and time
        if ($request->filled('due_time')) {
            $validated['due_date'] = $validated['due_date'] . ' ' . $request->due_time;
        }

        $validated['is_published'] = $request->has('is_published');

        $assignment->update($validated);

        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment updated successfully!');
    }

    public function destroy(Assignment $assignment)
    {
        $teacher = auth()->user();
        
        // Verify teacher owns this assignment
        if ($assignment->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized');
        }

        $assignment->delete();
        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment deleted successfully!');
    }
}
