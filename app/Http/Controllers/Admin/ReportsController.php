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
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function analytics(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Attendance trends (last 30 days by default)
        $attendanceTrends = [];
        $currentDate = Carbon::parse($startDate);
        while ($currentDate <= Carbon::parse($endDate)) {
            $dateStr = $currentDate->format('Y-m-d');
            $total = Attendance::whereDate('date', $dateStr)->count();
            $present = Attendance::whereDate('date', $dateStr)->where('status', 'present')->count();
            
            $attendanceTrends[] = [
                'date' => $currentDate->format('M d'),
                'total' => $total,
                'present' => $present,
                'percentage' => $total > 0 ? round(($present / $total) * 100, 2) : 0,
            ];
            $currentDate->addDay();
        }

        // Grade distribution
        $grades = Grade::with('enrollment')
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->get();

        $gradeDistribution = [
            'A' => 0,
            'B' => 0,
            'C' => 0,
            'D' => 0,
            'F' => 0,
        ];

        foreach ($grades as $grade) {
            $percentage = $grade->enrollment->averageGrade();
            if ($percentage >= 90) $gradeDistribution['A']++;
            elseif ($percentage >= 80) $gradeDistribution['B']++;
            elseif ($percentage >= 70) $gradeDistribution['C']++;
            elseif ($percentage >= 60) $gradeDistribution['D']++;
            else $gradeDistribution['F']++;
        }

        // Course performance
        $coursePerformance = Course::with(['enrollments.grades'])->get()->map(function ($course) {
            $enrollments = $course->enrollments;
            $averages = $enrollments->map(function ($enrollment) {
                return $enrollment->averageGrade();
            })->filter(function ($avg) {
                return $avg > 0;
            });

            return [
                'course' => $course->code . ' - ' . $course->name,
                'average' => $averages->count() > 0 ? round($averages->average(), 2) : 0,
                'students' => $enrollments->count(),
            ];
        })->sortByDesc('average')->take(10)->values();

        // Attendance by course
        $attendanceByCourse = Course::with(['enrollments.attendances' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }])->get()->map(function ($course) use ($startDate, $endDate) {
            $totalAttendance = Attendance::whereHas('enrollment', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            })->whereBetween('date', [$startDate, $endDate])->count();

            $presentCount = Attendance::whereHas('enrollment', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            })->whereBetween('date', [$startDate, $endDate])
              ->where('status', 'present')->count();

            return [
                'course' => $course->code,
                'total' => $totalAttendance,
                'present' => $presentCount,
                'percentage' => $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100, 2) : 0,
            ];
        })->sortByDesc('percentage')->take(10)->values();

        return response()->json([
            'attendance_trends' => $attendanceTrends,
            'grade_distribution' => $gradeDistribution,
            'course_performance' => $coursePerformance,
            'attendance_by_course' => $attendanceByCourse,
        ]);
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

    public function attendancePdf(Request $request)
    {
        $courseId = $request->input('course_id');
        $date = $request->input('date', today()->toDateString());
        
        if ($courseId) {
            $course = Course::with(['enrollments.student', 'enrollments.attendances' => function ($query) use ($date) {
                $query->where('date', $date);
            }])->findOrFail($courseId);
            
            $enrollments = $course->enrollments;
            $selectedDate = Carbon::parse($date);
            
            $pdf = Pdf::loadView('admin.reports.pdf.attendance-course', compact('course', 'enrollments', 'selectedDate'));
            return $pdf->download("attendance-{$course->code}-{$date}.pdf");
        }
        
        // Overall attendance report with date range
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        
        $courses = Course::with(['enrollments.attendances'])->get();
        $attendanceData = $courses->map(function ($course) use ($startDate, $endDate) {
            $totalAttendance = Attendance::whereHas('enrollment', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })->whereBetween('date', [$startDate, $endDate])->count();

            $presentCount = Attendance::whereHas('enrollment', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })->whereBetween('date', [$startDate, $endDate])
              ->where('status', 'present')->count();

            $percentage = $totalAttendance > 0 ? ($presentCount / $totalAttendance) * 100 : 0;

            return [
                'course' => $course,
                'total' => $totalAttendance,
                'present' => $presentCount,
                'percentage' => round($percentage, 2),
            ];
        });
        
        $dateRange = "{$startDate}_to_{$endDate}";
        $pdf = Pdf::loadView('admin.reports.pdf.attendance-overview', compact('attendanceData', 'startDate', 'endDate'));
        return $pdf->download("attendance-overview-{$dateRange}.pdf");
    }

    public function gradesPdf(Request $request)
    {
        $courseId = $request->input('course_id');
        
        if ($courseId) {
            $course = Course::with(['enrollments.student', 'enrollments.grades'])->findOrFail($courseId);
            $enrollments = $course->enrollments;
            
            $pdf = Pdf::loadView('admin.reports.pdf.grades-course', compact('course', 'enrollments'));
            return $pdf->download("grades-{$course->code}.pdf");
        }
        
        // Overall grades report
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
        
        $pdf = Pdf::loadView('admin.reports.pdf.grades-overview', compact('gradeData'));
        return $pdf->download('grades-overview-'.today()->format('Y-m-d').'.pdf');
    }

    public function studentTranscriptPdf(User $student)
    {
        if (!$student->hasRole('student')) {
            abort(404, 'Student not found');
        }

        $enrollments = $student->enrollments()
            ->with(['course.teacher', 'grades'])
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf.student-transcript', compact('student', 'enrollments'));
        return $pdf->download("transcript-{$student->name}-".today()->format('Y-m-d').'.pdf');
    }
}
