<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\SitemapController;

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

Route::get('/', function () {
    return view('welcome');
});

// Public site pages
Route::view('/about', 'pages.about')->name('about');
Route::view('/admissions', 'pages.admissions')->name('admissions');
Route::view('/academics', 'pages.academics')->name('academics');
Route::view('/contact', 'pages.contact')->name('contact');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Students management (Teacher and Admin)
Route::middleware(['auth', 'role_or_permission:admin|teacher'])->group(function () {
    Route::get('/students', [App\Http\Controllers\StudentsController::class, 'index'])->name('students.index');
    Route::get('/students/export', [App\Http\Controllers\StudentsController::class, 'export'])->name('students.export');
});

// CSV Import (Admin only)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/students/import', [App\Http\Controllers\StudentsController::class, 'import'])->name('students.import');
});

// Admin-only routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', function () {
        return 'Manage Users - Admin Only';
    })->name('users');
    
    Route::get('/settings', function () {
        return 'System Settings - Admin Only';
    })->name('settings');
});

// Teacher routes
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/gradebook', [App\Http\Controllers\Teacher\GradebookController::class, 'index'])->name('gradebook.index');
    Route::get('/gradebook/{course}', [App\Http\Controllers\Teacher\GradebookController::class, 'show'])->name('gradebook.show');
    Route::post('/gradebook/enrollment/{enrollment}/grade', [App\Http\Controllers\Teacher\GradebookController::class, 'storeGrade'])->name('gradebook.store');
    Route::patch('/gradebook/grade/{grade}', [App\Http\Controllers\Teacher\GradebookController::class, 'updateGrade'])->name('gradebook.update');
});

// Student routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/grades', [App\Http\Controllers\Student\GradesController::class, 'index'])->name('grades.index');
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

// Sitemap
Route::get('/sitemap.xml', SitemapController::class);
