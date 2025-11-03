<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fee;
use App\Models\FeeStructure;
use App\Models\User;
use Carbon\Carbon;

class FeeSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::role('student')->get();
        $admin = User::role('admin')->first();
        $feeStructures = FeeStructure::all();

        if ($students->isEmpty() || $feeStructures->isEmpty() || !$admin) {
            return;
        }

        $currentDate = Carbon::now();
        
        foreach ($students as $student) {
            foreach ($feeStructures as $structure) {
                // Determine due date based on frequency
                $dueDate = match($structure->frequency) {
                    'one_time' => $currentDate->copy()->addDays(30),
                    'monthly' => $currentDate->copy()->addMonth(),
                    'quarterly' => $currentDate->copy()->addMonths(3),
                    'semester' => $currentDate->copy()->addMonths(6),
                    'yearly' => $currentDate->copy()->addYear(),
                    default => $currentDate->copy()->addMonth(),
                };

                // Create fees for this year/semester
                // For recurring fees, create multiple instances
                $instances = match($structure->frequency) {
                    'monthly' => 6, // 6 months of fees
                    'quarterly' => 2, // 2 quarters
                    'semester' => 2, // 2 semesters
                    'yearly' => 1,
                    'one_time' => 1,
                    default => 1,
                };

                for ($i = 0; $i < $instances; $i++) {
                    // Skip one_time fees after first instance
                    if ($structure->frequency === 'one_time' && $i > 0) {
                        continue;
                    }

                    $instanceDueDate = match($structure->frequency) {
                        'monthly' => $currentDate->copy()->addMonths($i + 1),
                        'quarterly' => $currentDate->copy()->addMonths(($i + 1) * 3),
                        'semester' => $currentDate->copy()->addMonths(($i + 1) * 6),
                        'yearly' => $currentDate->copy()->addYear(),
                        'one_time' => $currentDate->copy()->addDays(30),
                        default => $currentDate->copy()->addMonths($i + 1),
                    };

                    // Randomly set some fees as paid, partial, or overdue
                    $statusOptions = ['pending', 'pending', 'pending', 'paid', 'partial', 'overdue'];
                    $status = $statusOptions[array_rand($statusOptions)];

                    // Make some due dates in the past for overdue fees
                    if ($status === 'overdue' && $i === 0) {
                        $instanceDueDate = $currentDate->copy()->subDays(rand(5, 30));
                    }

                    Fee::create([
                        'student_id' => $student->id,
                        'fee_structure_id' => $structure->id,
                        'amount' => $structure->amount,
                        'due_date' => $instanceDueDate,
                        'status' => $status,
                        'notes' => rand(0, 1) ? "Fee for {$structure->name}" : null,
                        'created_by' => $admin->id,
                    ]);
                }
            }
        }
    }
}
