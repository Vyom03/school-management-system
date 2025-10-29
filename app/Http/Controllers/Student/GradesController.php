<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
