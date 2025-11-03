<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Assignment::with(['course', 'teacher', 'submissions']);

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
        $courses = Course::all();

        return view('admin.assignments.index', compact('assignments', 'courses'));
    }

    public function create()
    {
        $courses = Course::all();
        $teachers = User::role('teacher')->get();
        return view('admin.assignments.create', compact('courses', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:users,id',
            'due_date' => 'required|date',
            'due_time' => 'nullable|date_format:H:i',
            'max_score' => 'required|numeric|min:0',
            'allowed_file_types' => 'nullable|string',
            'max_file_size' => 'nullable|integer|min:1',
            'is_published' => 'boolean',
        ]);

        // Combine date and time
        if ($request->filled('due_time')) {
            $validated['due_date'] = $validated['due_date'] . ' ' . $request->due_time;
        }

        $validated['is_published'] = $request->has('is_published');

        Assignment::create($validated);

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Assignment created successfully!');
    }

    public function show(Assignment $assignment)
    {
        $assignment->load(['course', 'teacher', 'submissions.student', 'submissions.files']);
        
        $submissions = $assignment->submissions()
            ->with(['student', 'files'])
            ->latest()
            ->get();

        return view('admin.assignments.show', compact('assignment', 'submissions'));
    }

    public function edit(Assignment $assignment)
    {
        $courses = Course::all();
        $teachers = User::role('teacher')->get();
        return view('admin.assignments.edit', compact('assignment', 'courses', 'teachers'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:users,id',
            'due_date' => 'required|date',
            'due_time' => 'nullable|date_format:H:i',
            'max_score' => 'required|numeric|min:0',
            'allowed_file_types' => 'nullable|string',
            'max_file_size' => 'nullable|integer|min:1',
            'is_published' => 'boolean',
        ]);

        // Combine date and time
        if ($request->filled('due_time')) {
            $validated['due_date'] = $validated['due_date'] . ' ' . $request->due_time;
        }

        $validated['is_published'] = $request->has('is_published');

        $assignment->update($validated);

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Assignment updated successfully!');
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return redirect()->route('admin.assignments.index')
            ->with('success', 'Assignment deleted successfully!');
    }
}
