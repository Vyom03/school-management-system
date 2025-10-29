<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $courses = Course::withCount('enrollments')->get();
        return view('admin.attendance.index', compact('courses'));
    }

    public function show(Course $course, Request $request)
    {
        $date = $request->input('date', today()->toDateString());
        $selectedDate = Carbon::parse($date);

        $enrollments = $course->enrollments()
            ->with(['student', 'attendances' => function ($query) use ($date) {
                $query->where('date', $date);
            }])
            ->get();

        // Calculate attendance stats for the course
        $stats = [
            'total_students' => $enrollments->count(),
            'present' => $enrollments->filter(function ($e) {
                return $e->attendances->first()?->status === 'present';
            })->count(),
            'absent' => $enrollments->filter(function ($e) {
                return $e->attendances->first()?->status === 'absent';
            })->count(),
            'late' => $enrollments->filter(function ($e) {
                return $e->attendances->first()?->status === 'late';
            })->count(),
            'excused' => $enrollments->filter(function ($e) {
                return $e->attendances->first()?->status === 'excused';
            })->count(),
            'not_marked' => $enrollments->filter(function ($e) {
                return $e->attendances->first() === null;
            })->count(),
        ];

        return view('admin.attendance.show', compact('course', 'enrollments', 'selectedDate', 'stats'));
    }

    public function studentReport(User $student)
    {
        if (!$student->hasRole('student')) {
            abort(404, 'Student not found');
        }

        $enrollments = $student->enrollments()
            ->with(['course.teacher', 'attendances'])
            ->get();

        // Calculate stats for each enrollment
        $enrollments->each(function ($enrollment) {
            $enrollment->attendance_stats = $enrollment->attendanceStats();
        });

        return view('admin.attendance.student-report', compact('student', 'enrollments'));
    }
}
