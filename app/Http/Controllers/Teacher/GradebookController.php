<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class GradebookController extends Controller
{
    public function index()
    {
        $courses = Course::where('teacher_id', auth()->id())
            ->withCount('enrollments')
            ->get();

        return view('teacher.gradebook.index', compact('courses'));
    }

    public function show(Course $course, Request $request)
    {
        // Verify teacher owns this course
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $search = $request->input('search');

        $enrollments = $course->enrollments()
            ->with(['student', 'grades'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->get();

        return view('teacher.gradebook.show', compact('course', 'enrollments', 'search'));
    }

    public function storeGrade(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'assignment_name' => 'required|string|max:255',
            'score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|min:1',
            'comments' => 'nullable|string',
        ]);

        // Verify teacher owns the course
        if ($enrollment->course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        Grade::create([
            'enrollment_id' => $enrollment->id,
            'assignment_name' => $request->assignment_name,
            'score' => $request->score,
            'max_score' => $request->max_score,
            'comments' => $request->comments,
            'graded_by' => auth()->id(),
        ]);

        return back()->with('success', 'Grade added successfully!');
    }

    public function updateGrade(Request $request, Grade $grade)
    {
        $request->validate([
            'score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|min:1',
            'comments' => 'nullable|string',
        ]);

        // Verify teacher owns the course
        if ($grade->enrollment->course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $grade->update([
            'score' => $request->score,
            'max_score' => $request->max_score,
            'comments' => $request->comments,
        ]);

        return back()->with('success', 'Grade updated successfully!');
    }
}
