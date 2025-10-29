<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function index()
    {
        $courses = Course::with('teacher')->withCount('enrollments')->get();
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $teachers = User::role('teacher')->get();
        return view('admin.courses.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:courses,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'semester' => 'required|string|max:50',
            'teacher_id' => 'required|exists:users,id',
        ]);

        Course::create($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully!');
    }

    public function edit(Course $course)
    {
        $teachers = User::role('teacher')->get();
        return view('admin.courses.edit', compact('course', 'teachers'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:courses,code,' . $course->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'semester' => 'required|string|max:50',
            'teacher_id' => 'required|exists:users,id',
        ]);

        $course->update($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully!');
    }
}
