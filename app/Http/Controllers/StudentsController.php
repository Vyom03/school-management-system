<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $students = User::role('student')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->withCount('enrollments')
            ->orderBy('name')
            ->paginate(20);

        return view('students.index', compact('students', 'search'));
    }

    public function export()
    {
        $students = User::role('student')
            ->with('enrollments.course')
            ->orderBy('name')
            ->get();

        $filename = 'students_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, ['ID', 'Name', 'Email', 'Enrolled Courses', 'Created At']);
            
            // CSV Rows
            foreach ($students as $student) {
                $courses = $student->enrollments->pluck('course.name')->implode(', ');
                fputcsv($file, [
                    $student->id,
                    $student->name,
                    $student->email,
                    $courses ?: 'None',
                    $student->created_at->format('Y-m-d'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        // Only admins can import
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        
        // Remove header row
        $header = array_shift($data);
        
        $imported = 0;
        $errors = [];
        
        foreach ($data as $index => $row) {
            $rowNumber = $index + 2; // +2 because we removed header and arrays start at 0
            
            // Expecting: Name, Email
            if (count($row) < 2) {
                $errors[] = "Row $rowNumber: Missing required fields";
                continue;
            }
            
            $name = trim($row[0]);
            $email = trim($row[1]);
            
            if (empty($name) || empty($email)) {
                $errors[] = "Row $rowNumber: Name and email are required";
                continue;
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Row $rowNumber: Invalid email format ($email)";
                continue;
            }
            
            // Check if user already exists
            if (User::where('email', $email)->exists()) {
                $errors[] = "Row $rowNumber: Email already exists ($email)";
                continue;
            }
            
            // Create student
            try {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make('password'), // Default password
                    'email_verified_at' => now(),
                ]);
                
                $user->assignRole('student');
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row $rowNumber: Failed to create user - " . $e->getMessage();
            }
        }
        
        $message = "$imported students imported successfully.";
        if (count($errors) > 0) {
            $message .= " " . count($errors) . " errors occurred.";
        }
        
        return back()->with([
            'success' => $message,
            'import_errors' => $errors,
        ]);
    }
}
