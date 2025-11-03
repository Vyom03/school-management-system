<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParentRegistrationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ParentRegistrationCodeController extends Controller
{
    public function index()
    {
        $codes = ParentRegistrationCode::with(['student', 'creator'])
            ->latest()
            ->paginate(20);

        return view('admin.parent-codes.index', compact('codes'));
    }

    public function create()
    {
        $students = User::role('student')->orderBy('name')->get();
        $gradeLevels = User::getAvailableGrades();
        return view('admin.parent-codes.create', compact('students', 'gradeLevels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'email' => ['nullable', 'email', 'max:255'],
            'relationship' => ['required', 'string', 'in:parent,guardian,other'],
            'expires_at' => ['nullable', 'date', 'after:today'],
            'count' => ['required', 'integer', 'min:1', 'max:10'], // Generate multiple codes
        ]);

        $codes = [];
        
        for ($i = 0; $i < $request->count; $i++) {
            $code = ParentRegistrationCode::create([
                'student_id' => $request->student_id,
                'code' => ParentRegistrationCode::generateCode(),
                'email' => $request->email,
                'relationship' => $request->relationship,
                'expires_at' => $request->expires_at ? now()->parse($request->expires_at) : now()->addMonths(6),
                'created_by' => auth()->id(),
            ]);
            
            $codes[] = $code;
        }

        $student = User::findOrFail($request->student_id);
        $message = count($codes) > 1 
            ? "Generated " . count($codes) . " registration codes for {$student->name}."
            : "Generated registration code for {$student->name}.";

        return redirect()->route('admin.parent-codes.index')
            ->with('success', $message)
            ->with('generated_codes', $codes);
    }

    public function destroy(ParentRegistrationCode $parent_code)
    {
        if ($parent_code->used) {
            return back()->with('error', 'Cannot delete a code that has already been used.');
        }

        $parent_code->delete();

        return back()->with('success', 'Registration code deleted successfully.');
    }

    public function bulkCreate()
    {
        $gradeLevels = User::getAvailableGrades();
        return view('admin.parent-codes.bulk-create', compact('gradeLevels'));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'grade_level' => ['required', 'integer', 'between:1,12'],
            'expires_at' => ['required', 'date', 'after:today'],
            'relationship' => ['required', 'string', 'in:parent,guardian,other'],
            'codes_per_student' => ['required', 'integer', 'min:1', 'max:5'], // 1-5 codes per student
        ]);

        // Get all students in the selected grade
        $students = User::role('student')
            ->where('grade_level', $request->grade_level)
            ->orderBy('name')
            ->get();

        if ($students->count() === 0) {
            return back()->withErrors([
                'grade_level' => 'No students found in the selected grade level.',
            ])->withInput();
        }

        $allCodes = [];
        $expiresAt = now()->parse($request->expires_at);

        foreach ($students as $student) {
            for ($i = 0; $i < $request->codes_per_student; $i++) {
                $code = ParentRegistrationCode::create([
                    'student_id' => $student->id,
                    'code' => ParentRegistrationCode::generateCode(),
                    'email' => null, // No pre-approved email for bulk generation
                    'relationship' => $request->relationship,
                    'expires_at' => $expiresAt,
                    'created_by' => auth()->id(),
                ]);
                
                $allCodes[] = $code;
            }
        }

        // Load student relationships for PDF and group by student
        $codesCollection = collect($allCodes);
        
        // Eager load students to avoid N+1 queries
        $studentIds = $codesCollection->pluck('student_id')->unique();
        $students = User::whereIn('id', $studentIds)->get()->keyBy('id');
        
        // Attach students to codes and group by student
        foreach ($codesCollection as $code) {
            if (isset($students[$code->student_id])) {
                $code->setRelation('student', $students[$code->student_id]);
            }
        }

        // Group codes by student for better PDF organization
        $codesByStudent = $codesCollection->groupBy('student_id')->filter(function ($codes) {
            return $codes->isNotEmpty() && $codes->first() && $codes->first()->student;
        });

        if ($codesByStudent->isEmpty()) {
            return back()->withErrors([
                'error' => 'No valid codes could be generated. Please check that students exist in the selected grade level.',
            ])->withInput();
        }

        // Generate PDF
        $gradeName = User::getAvailableGrades()[$request->grade_level];
        
        try {
            // Prepare data for PDF - convert collection to array to avoid issues
            $pdfData = [];
            foreach ($codesByStudent as $studentId => $studentCodes) {
                $firstCode = $studentCodes->first();
                if (!$firstCode || !$firstCode->student) {
                    continue;
                }
                $student = $firstCode->student;
                $codesList = [];
                foreach ($studentCodes as $code) {
                    if ($code->code) {
                        $codesList[] = $code->code;
                    }
                }
                
                $pdfData[] = [
                    'student_name' => $student->name ?? 'N/A',
                    'student_email' => $student->email ?? 'N/A',
                    'codes' => implode(', ', $codesList),
                ];
            }

            $pdf = Pdf::loadView('admin.parent-codes.pdf.bulk-codes', [
                'pdfData' => $pdfData,
                'gradeLevel' => $request->grade_level,
                'gradeName' => $gradeName,
                'expiresAt' => $expiresAt,
                'relationship' => $request->relationship,
                'codesPerStudent' => $request->codes_per_student,
                'totalStudents' => count($pdfData),
                'totalCodes' => count($allCodes),
            ])->setPaper('a4', 'portrait');

            $filename = "parent-registration-codes-grade-{$request->grade_level}-" . now()->format('Y-m-d') . '.pdf';
            
            return $pdf->download($filename);
        } catch (\Throwable $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->withErrors([
                'error' => 'Failed to generate PDF: ' . $e->getMessage() . ' (Check logs for details)',
            ])->withInput();
        }
    }
}
