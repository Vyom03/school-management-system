<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $studentId = Auth::id();
        
        $enrollments = Auth::user()->enrollments()
            ->with(['course.teacher', 'attendances'])
            ->get();

        // Calculate stats for each enrollment
        $enrollments->each(function ($enrollment) {
            $enrollment->attendance_stats = $enrollment->attendanceStats();
        });

        return view('student.attendance.index', compact('enrollments'));
    }
}
