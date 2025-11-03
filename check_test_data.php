<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\FeeStructure;
use App\Models\Attendance;
use App\Models\Enrollment;

echo "=== TEST DATA VERIFICATION ===\n\n";

// Check Fees
echo "═══════════════════════════════════════════════════════════════════════════\n";
echo "FEE DATA:\n";
echo "═══════════════════════════════════════════════════════════════════════════\n";
$feeStructures = FeeStructure::count();
$fees = Fee::count();
$payments = Payment::count();

echo "Fee Structures: " . $feeStructures . "\n";
echo "Fees Assigned:  " . $fees . "\n";
echo "Payments:       " . $payments . "\n";

if ($fees > 0) {
    $studentsWithFees = Fee::distinct('student_id')->count('student_id');
    echo "Students with fees: " . $studentsWithFees . "\n";
    
    $stats = [
        'pending' => Fee::where('status', 'pending')->count(),
        'paid' => Fee::where('status', 'paid')->count(),
        'partial' => Fee::where('status', 'partial')->count(),
        'overdue' => Fee::where('status', 'overdue')->count(),
    ];
    echo "\nFee Status Breakdown:\n";
    foreach ($stats as $status => $count) {
        echo "  - " . ucfirst($status) . ": " . $count . "\n";
    }
} else {
    echo "\n⚠️  NO FEE DATA FOUND - Fees need to be seeded!\n";
}

echo "\n";

// Check Attendance
echo "═══════════════════════════════════════════════════════════════════════════\n";
echo "ATTENDANCE DATA:\n";
echo "═══════════════════════════════════════════════════════════════════════════\n";
$attendances = Attendance::count();
$enrollments = Enrollment::count();

echo "Total Attendance Records: " . $attendances . "\n";
echo "Total Enrollments:        " . $enrollments . "\n";

if ($attendances > 0) {
    $studentsWithAttendance = Attendance::join('enrollments', 'attendances.enrollment_id', '=', 'enrollments.id')
        ->distinct('enrollments.student_id')
        ->count('enrollments.student_id');
    echo "Students with attendance: " . $studentsWithAttendance . "\n";
    
    $statusCounts = Attendance::selectRaw('status, count(*) as count')
        ->groupBy('status')
        ->get();
    echo "\nAttendance Status Breakdown:\n";
    foreach ($statusCounts as $stat) {
        echo "  - " . ucfirst($stat->status) . ": " . $stat->count . "\n";
    }
} else {
    echo "\n⚠️  NO ATTENDANCE DATA FOUND - Attendance needs to be seeded!\n";
}

echo "\n";

// Check Courses and Enrollments
echo "═══════════════════════════════════════════════════════════════════════════\n";
echo "COURSE & ENROLLMENT DATA:\n";
echo "═══════════════════════════════════════════════════════════════════════════\n";
$courses = \App\Models\Course::count();
echo "Courses:        " . $courses . "\n";
echo "Enrollments:    " . $enrollments . "\n";

if ($enrollments > 0) {
    $studentsEnrolled = Enrollment::distinct('student_id')->count('student_id');
    echo "Students enrolled: " . $studentsEnrolled . "\n";
}

echo "\n═══════════════════════════════════════════════════════════════════════════\n";
echo "SUMMARY:\n";
echo "  ✓ Fee Data:      " . ($fees > 0 ? "YES ({$fees} fees)" : "NO - Needs seeding") . "\n";
echo "  ✓ Attendance:    " . ($attendances > 0 ? "YES ({$attendances} records)" : "NO - Needs seeding") . "\n";
echo "═══════════════════════════════════════════════════════════════════════════\n";

