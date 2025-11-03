<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class GradesController extends Controller
{
    public function index()
    {
        $enrollments = auth()->user()
            ->hasRole('student') 
            ? auth()->user()->enrollments()->with(['course.teacher', 'grades'])->get()
            : collect();

        return view('student.grades.index', compact('enrollments'));
    }

    public function transcriptPdf()
    {
        $student = auth()->user();
        $enrollments = $student->enrollments()
            ->with(['course.teacher', 'grades'])
            ->get();

        $pdf = Pdf::loadView('student.grades.pdf.transcript', compact('student', 'enrollments'));
        return $pdf->download("transcript-".today()->format('Y-m-d').'.pdf');
    }
}
