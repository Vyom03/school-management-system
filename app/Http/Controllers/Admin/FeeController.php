<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\FeeStructure;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeeController extends Controller
{
    /**
     * Display fee management dashboard
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $studentId = $request->input('student_id');

        $query = Fee::with(['student', 'feeStructure', 'payments']);

        if ($search) {
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        $fees = $query->orderBy('due_date', 'desc')->paginate(20);
        $students = User::role('student')->orderBy('name')->get();
        $feeStructures = FeeStructure::active()->get();

        // Statistics
        $stats = [
            'total_fees' => Fee::count(),
            'pending_fees' => Fee::where('status', 'pending')->count(),
            'paid_fees' => Fee::where('status', 'paid')->count(),
            'overdue_fees' => Fee::where('status', 'overdue')->count(),
            'total_amount_due' => Fee::whereIn('status', ['pending', 'partial', 'overdue'])
                ->sum(DB::raw('amount - (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.fee_id = fees.id)')),
            'total_collected' => Payment::sum('amount'),
        ];

        return view('admin.fees.index', compact('fees', 'students', 'feeStructures', 'stats', 'search', 'status', 'studentId'));
    }

    /**
     * Display fee structures management
     */
    public function structures()
    {
        $structures = FeeStructure::orderBy('name')->get();
        return view('admin.fees.structures', compact('structures'));
    }

    /**
     * Store fee structure
     */
    public function storeStructure(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:one_time,monthly,quarterly,semester,yearly',
            'grade_level' => 'nullable|integer|between:1,12',
            'is_active' => 'boolean',
        ]);

        FeeStructure::create($request->all());

        return redirect()->route('admin.fees.structures')->with('success', 'Fee structure created successfully.');
    }

    /**
     * Update fee structure
     */
    public function updateStructure(Request $request, FeeStructure $feeStructure)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:one_time,monthly,quarterly,semester,yearly',
            'grade_level' => 'nullable|integer|between:1,12',
            'is_active' => 'boolean',
        ]);

        $feeStructure->update($request->all());

        return redirect()->route('admin.fees.structures')->with('success', 'Fee structure updated successfully.');
    }

    /**
     * Delete fee structure
     */
    public function destroyStructure(FeeStructure $feeStructure)
    {
        if ($feeStructure->fees()->count() > 0) {
            return redirect()->route('admin.fees.structures')->with('error', 'Cannot delete fee structure with existing fees.');
        }

        $feeStructure->delete();
        return redirect()->route('admin.fees.structures')->with('success', 'Fee structure deleted successfully.');
    }

    /**
     * Assign fee to student(s)
     */
    public function assignFee(Request $request)
    {
        $request->validate([
            'fee_structure_id' => 'required|exists:fee_structures,id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id',
            'amount' => 'nullable|numeric|min:0',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $feeStructure = FeeStructure::findOrFail($request->fee_structure_id);
        $amount = $request->amount ?? $feeStructure->amount;

        foreach ($request->student_ids as $studentId) {
            Fee::create([
                'student_id' => $studentId,
                'fee_structure_id' => $feeStructure->id,
                'amount' => $amount,
                'due_date' => $request->due_date,
                'status' => 'pending',
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->route('admin.fees.index')->with('success', 'Fee assigned to ' . count($request->student_ids) . ' student(s).');
    }

    /**
     * Show fee details
     */
    public function show(Fee $fee)
    {
        $fee->load(['student', 'feeStructure', 'payments.recorder']);
        return view('admin.fees.show', compact('fee'));
    }

    /**
     * Record payment
     */
    public function recordPayment(Request $request, Fee $fee)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $fee->remaining_amount,
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,check,online,card,other',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Payment::create([
            'fee_id' => $fee->id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'notes' => $request->notes,
            'recorded_by' => auth()->id(),
        ]);

        return redirect()->route('admin.fees.show', $fee)->with('success', 'Payment recorded successfully.');
    }

    /**
     * Update fee
     */
    public function update(Request $request, Fee $fee)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,paid,partial,overdue,waived',
            'notes' => 'nullable|string',
        ]);

        $fee->update($request->all());
        $fee->updateStatus();

        return redirect()->route('admin.fees.show', $fee)->with('success', 'Fee updated successfully.');
    }

    /**
     * Delete fee
     */
    public function destroy(Fee $fee)
    {
        if ($fee->payments()->count() > 0) {
            return redirect()->route('admin.fees.index')->with('error', 'Cannot delete fee with existing payments.');
        }

        $fee->delete();
        return redirect()->route('admin.fees.index')->with('success', 'Fee deleted successfully.');
    }
}
