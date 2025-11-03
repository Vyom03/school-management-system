<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeeController extends Controller
{
    /**
     * Display student's fees
     */
    public function index(Request $request)
    {
        $student = auth()->user();
        
        $status = $request->input('status');
        $query = $student->fees()->with(['feeStructure', 'payments']);

        if ($status) {
            $query->where('status', $status);
        }

        $fees = $query->orderBy('due_date', 'asc')->get();

        // Statistics
        $stats = [
            'total_due' => $student->fees()
                ->whereIn('status', ['pending', 'partial', 'overdue'])
                ->sum(DB::raw('amount - (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.fee_id = fees.id)')),
            'pending_count' => $student->fees()->where('status', 'pending')->count(),
            'overdue_count' => $student->fees()->where('status', 'overdue')->count(),
            'paid_count' => $student->fees()->where('status', 'paid')->count(),
        ];

        return view('student.fees.index', compact('fees', 'stats', 'status'));
    }

    /**
     * Show fee details
     */
    public function show(Fee $fee)
    {
        // Ensure fee belongs to authenticated student
        if ($fee->student_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $fee->load(['feeStructure', 'payments.recorder']);
        return view('student.fees.show', compact('fee'));
    }
}
