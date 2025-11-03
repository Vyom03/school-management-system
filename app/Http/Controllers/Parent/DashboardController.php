<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $parent = Auth::guard('parent')->user();
        $students = $parent->students()->with(['enrollments.course', 'enrollments.grades'])->get();
        
        // Get stats for all children
        $totalChildren = $students->count();
        $totalCourses = $students->flatMap->enrollments->unique('course_id')->count();
        
        // Recent announcements
        $announcements = \App\Models\Announcement::published()
            ->where(function($query) {
                $query->where('audience', 'All')
                      ->orWhere('audience', 'Parents');
            })
            ->latest()
            ->take(5)
            ->get();

        return view('parent.dashboard', compact('parent', 'students', 'totalChildren', 'totalCourses', 'announcements'));
    }
}
