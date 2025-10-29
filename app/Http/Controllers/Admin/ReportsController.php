<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Attendance;
use App\Models\Grade;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        // Overall statistics
        $stats = [
            'total_students' => User::role('student')->count(),
            'total_teachers' => User::role('teacher')->count(),
            'total_courses' => Course::count(),
            'total_enrollments' => Enrollment::count(),
            'attendance_today' => Attendance::whereDate('date', today())->count(),
            'grades_entered' => Grade::count(),
        ];

        // Recent activity
        $recent_enrollments = Enrollment::with(['student', 'course'])
            ->latest()
            ->take(10)
            ->get();

        $recent_grades = Grade::with(['enrollment.student', 'enrollment.course'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.reports.index', compact('stats', 'recent_enrollments', 'recent_grades'));
    }

    public function attendance()
    {
        // Attendance statistics by course
        $courses = Course::with(['enrollments.attendances'])->get();
        
        $attendanceData = $courses->map(function ($course) {
            $totalAttendance = Attendance::whereHas('enrollment', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })->count();

            $presentCount = Attendance::whereHas('enrollment', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })->where('status', 'present')->count();

            $percentage = $totalAttendance > 0 ? ($presentCount / $totalAttendance) * 100 : 0;

            return [
                'course' => $course,
                'total' => $totalAttendance,
                'present' => $presentCount,
                'percentage' => round($percentage, 2),
            ];
        });

        return view('admin.reports.attendance', compact('attendanceData'));
    }

    public function grades()
    {
        // Grade statistics by course
        $courses = Course::with(['enrollments.grades'])->get();
        
        $gradeData = $courses->map(function ($course) {
            $enrollments = $course->enrollments;
            $averages = $enrollments->map(function ($enrollment) {
                return $enrollment->averageGrade();
            })->filter(function ($avg) {
                return $avg > 0;
            });

            return [
                'course' => $course,
                'students' => $enrollments->count(),
                'average' => $averages->count() > 0 ? round($averages->average(), 2) : 0,
                'highest' => $averages->count() > 0 ? round($averages->max(), 2) : 0,
                'lowest' => $averages->count() > 0 ? round($averages->min(), 2) : 0,
            ];
        });

        return view('admin.reports.grades', compact('gradeData'));
    }
}
