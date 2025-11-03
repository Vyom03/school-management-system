<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function show(User $student)
    {
        $parent = Auth::guard('parent')->user();
        
        // Verify that this student is linked to the logged-in parent
        if (!$parent->students->contains($student->id)) {
            abort(403, 'You do not have access to this student\'s information.');
        }

        // Load student data
        $student->load([
            'enrollments.course.teacher',
            'enrollments.grades',
            'enrollments.attendances',
            'fees.feeStructure',
            'fees.payments',
            'submissions.assignment'
        ]);

        // Calculate statistics
        $enrollments = $student->enrollments;
        
        // Calculate average grade
        $totalGrades = $enrollments->flatMap->grades;
        $averageGrade = null;
        if ($totalGrades->count() > 0) {
            $totalScore = $totalGrades->sum('score');
            $totalMaxScore = $totalGrades->sum('max_score');
            if ($totalMaxScore > 0) {
                $averageGrade = round(($totalScore / $totalMaxScore) * 100, 1);
            }
        }

        // Calculate attendance stats
        $totalAttendance = $enrollments->flatMap->attendances;
        $presentCount = $totalAttendance->where('status', 'present')->count();
        $attendancePercentage = $totalAttendance->count() > 0 
            ? round(($presentCount / $totalAttendance->count()) * 100, 1) 
            : 0;

        // Fee statistics
        $totalFees = $student->fees;
        $paidFees = $totalFees->where('status', 'paid')->sum('amount');
        $pendingFees = $totalFees->where('status', 'pending')->sum('amount');

        return view('parent.student.show', compact(
            'student', 
            'enrollments', 
            'averageGrade', 
            'attendancePercentage',
            'totalFees',
            'paidFees',
            'pendingFees'
        ));
    }
}
