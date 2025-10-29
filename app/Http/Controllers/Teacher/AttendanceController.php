<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $teacherId = Auth::id();
        $courses = Course::where('teacher_id', $teacherId)
            ->withCount('enrollments')
            ->get();

        return view('teacher.attendance.index', compact('courses'));
    }

    public function show(Course $course, Request $request)
    {
        // Verify teacher owns this course
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $date = $request->input('date', today()->toDateString());
        $selectedDate = Carbon::parse($date);

        $enrollments = $course->enrollments()
            ->with(['student', 'attendances' => function ($query) use ($date) {
                $query->where('date', $date);
            }])
            ->get();

        return view('teacher.attendance.show', compact('course', 'enrollments', 'selectedDate'));
    }

    public function store(Request $request, Course $course)
    {
        // Verify teacher owns this course
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent,late,excused',
            'notes' => 'array',
            'notes.*' => 'nullable|string|max:500',
        ]);

        $date = $request->input('date');
        $attendanceData = $request->input('attendance');
        $notesData = $request->input('notes', []);

        foreach ($attendanceData as $enrollmentId => $status) {
            $enrollment = Enrollment::findOrFail($enrollmentId);
            
            // Verify enrollment belongs to this course
            if ($enrollment->course_id !== $course->id) {
                continue;
            }

            Attendance::updateOrCreate(
                [
                    'enrollment_id' => $enrollmentId,
                    'date' => $date,
                ],
                [
                    'status' => $status,
                    'notes' => $notesData[$enrollmentId] ?? null,
                    'marked_by' => Auth::id(),
                ]
            );
        }

        return back()->with('success', 'Attendance marked successfully for ' . Carbon::parse($date)->format('F j, Y'));
    }
}
