@php
    $user = Auth::user();
    
    if ($user->hasRole('admin')) {
        $dashboardView = 'dashboards.admin';
    } elseif ($user->hasRole('teacher')) {
        $dashboardView = 'dashboards.teacher';
    } elseif ($user->hasRole('student')) {
        $dashboardView = 'dashboards.student';
    } else {
        $dashboardView = 'dashboards.default';
    }
@endphp

@include($dashboardView)
