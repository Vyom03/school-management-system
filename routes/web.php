<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to login or dashboard based on auth status
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Announcements (all authenticated users)
    Route::get('/announcements', [App\Http\Controllers\AnnouncementsController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/{announcement}', [App\Http\Controllers\AnnouncementsController::class, 'show'])->name('announcements.show');
    
    // Calendar (all authenticated users)
    Route::get('/calendar', [App\Http\Controllers\CalendarController::class, 'index'])->name('calendar.index');
});

// Students management (Teacher and Admin)
Route::middleware(['auth', 'role_or_permission:admin|teacher'])->group(function () {
    Route::get('/students', [App\Http\Controllers\StudentsController::class, 'index'])->name('students.index');
    Route::get('/students/export', [App\Http\Controllers\StudentsController::class, 'export'])->name('students.export');
    Route::delete('/students/{student}', [App\Http\Controllers\StudentsController::class, 'destroy'])->name('students.destroy');
});

// CSV Import and Bulk Delete (Admin only)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/students/import', [App\Http\Controllers\StudentsController::class, 'import'])->name('students.import');
    Route::post('/students/bulk-delete', [App\Http\Controllers\StudentsController::class, 'bulkDelete'])->name('students.bulk-delete');
});

// Admin-only routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // User management
    Route::resource('users', App\Http\Controllers\Admin\UsersController::class);
    Route::post('/users/bulk-delete', [App\Http\Controllers\Admin\UsersController::class, 'bulkDelete'])->name('users.bulk-delete');
    
    // Courses management
    Route::resource('courses', App\Http\Controllers\Admin\CoursesController::class);
    
    // Reports
    Route::get('/reports', [App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/attendance', [App\Http\Controllers\Admin\ReportsController::class, 'attendance'])->name('reports.attendance');
    Route::get('/reports/grades', [App\Http\Controllers\Admin\ReportsController::class, 'grades'])->name('reports.grades');
    Route::get('/reports/attendance/pdf', [App\Http\Controllers\Admin\ReportsController::class, 'attendancePdf'])->name('reports.attendance.pdf');
    Route::get('/reports/grades/pdf', [App\Http\Controllers\Admin\ReportsController::class, 'gradesPdf'])->name('reports.grades.pdf');
    Route::get('/reports/transcript/{student}', [App\Http\Controllers\Admin\ReportsController::class, 'studentTranscriptPdf'])->name('reports.transcript.pdf');
    
    // Settings
    Route::view('/settings', 'admin.settings.index')->name('settings');
    
    // Attendance routes
    Route::get('/attendance', [App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/course/{course}', [App\Http\Controllers\Admin\AttendanceController::class, 'show'])->name('attendance.show');
    Route::get('/attendance/student/{student}', [App\Http\Controllers\Admin\AttendanceController::class, 'studentReport'])->name('attendance.student');
    Route::get('/attendance/student/{student}/pdf', [App\Http\Controllers\Admin\AttendanceController::class, 'studentReportPdf'])->name('attendance.student.pdf');
    
    // Announcements management
    Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementsController::class);
    
    // Events management
    Route::resource('events', App\Http\Controllers\Admin\EventsController::class);
    
    // Fee management
    Route::get('/fees', [App\Http\Controllers\Admin\FeeController::class, 'index'])->name('fees.index');
    
    // Fee structures (must be before /fees/{fee} to avoid route conflicts)
    Route::get('/fees/structures', [App\Http\Controllers\Admin\FeeController::class, 'structures'])->name('fees.structures');
    Route::post('/fees/structures', [App\Http\Controllers\Admin\FeeController::class, 'storeStructure'])->name('fees.structures.store');
    Route::put('/fees/structures/{feeStructure}', [App\Http\Controllers\Admin\FeeController::class, 'updateStructure'])->name('fees.structures.update');
    Route::delete('/fees/structures/{feeStructure}', [App\Http\Controllers\Admin\FeeController::class, 'destroyStructure'])->name('fees.structures.destroy');
    
    // Fee assign route (must be before /fees/{fee})
    Route::post('/fees/assign', [App\Http\Controllers\Admin\FeeController::class, 'assignFee'])->name('fees.assign');
    
    // Individual fee routes (keep these last)
    Route::get('/fees/{fee}', [App\Http\Controllers\Admin\FeeController::class, 'show'])->name('fees.show');
    Route::put('/fees/{fee}', [App\Http\Controllers\Admin\FeeController::class, 'update'])->name('fees.update');
    Route::delete('/fees/{fee}', [App\Http\Controllers\Admin\FeeController::class, 'destroy'])->name('fees.destroy');
    Route::post('/fees/{fee}/payment', [App\Http\Controllers\Admin\FeeController::class, 'recordPayment'])->name('fees.payment');
});

// Teacher routes
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/gradebook', [App\Http\Controllers\Teacher\GradebookController::class, 'index'])->name('gradebook.index');
    Route::get('/gradebook/{course}', [App\Http\Controllers\Teacher\GradebookController::class, 'show'])->name('gradebook.show');
    Route::post('/gradebook/enrollment/{enrollment}/grade', [App\Http\Controllers\Teacher\GradebookController::class, 'storeGrade'])->name('gradebook.store');
    Route::patch('/gradebook/grade/{grade}', [App\Http\Controllers\Teacher\GradebookController::class, 'updateGrade'])->name('gradebook.update');
    
    // Attendance routes
    Route::get('/attendance', [App\Http\Controllers\Teacher\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{course}', [App\Http\Controllers\Teacher\AttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/attendance/{course}', [App\Http\Controllers\Teacher\AttendanceController::class, 'store'])->name('attendance.store');
});

// Student routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/grades', [App\Http\Controllers\Student\GradesController::class, 'index'])->name('grades.index');
    Route::get('/grades/transcript/pdf', [App\Http\Controllers\Student\GradesController::class, 'transcriptPdf'])->name('grades.transcript.pdf');
    Route::get('/attendance', [App\Http\Controllers\Student\AttendanceController::class, 'index'])->name('attendance.index');
    
    // Fees
    Route::get('/fees', [App\Http\Controllers\Student\FeeController::class, 'index'])->name('fees.index');
    Route::get('/fees/{fee}', [App\Http\Controllers\Student\FeeController::class, 'show'])->name('fees.show');
});

require __DIR__.'/auth.php';

// Local mail test route
if (app()->environment('local')) {
    Route::get('/mail-test', function () {
        $recipient = env('CONTACT_RECIPIENT', (string) config('mail.from.address'));
        Mail::raw('This is a test email from your Laravel app.', function ($mail) use ($recipient) {
            $mail->to($recipient)->subject('Mail configuration test');
        });
        return 'Test email attempted to send to ' . $recipient . '. Check your inbox/spam.';
    });
    
    Route::get('/users-test', function () {
        $users = \App\Models\User::with('roles')->get();
        $html = '<h1>Users in Database</h1><table border="1" cellpadding="10"><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>';
        foreach ($users as $user) {
            $role = $user->roles->first()?->name ?? 'No role';
            $html .= "<tr><td>{$user->id}</td><td>{$user->name}</td><td>{$user->email}</td><td>{$role}</td></tr>";
        }
        $html .= '</table><p><strong>Password for all:</strong> password</p>';
        return $html;
    });
}
