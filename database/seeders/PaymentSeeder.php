<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Fee;
use App\Models\User;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::role('admin')->first();
        $fees = Fee::whereIn('status', ['paid', 'partial'])->get();

        if ($fees->isEmpty() || !$admin) {
            return;
        }

        $paymentMethods = ['cash', 'bank_transfer', 'check', 'online', 'card'];
        
        foreach ($fees as $fee) {
            $totalPaid = 0;
            
            if ($fee->status === 'paid') {
                // Create full payment
                $paymentDate = Carbon::parse($fee->due_date)->subDays(rand(0, 30));
                
                Payment::create([
                    'fee_id' => $fee->id,
                    'amount' => $fee->amount,
                    'payment_date' => $paymentDate,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                    'notes' => rand(0, 1) ? 'Full payment received' : null,
                    'recorded_by' => $admin->id,
                ]);
            } elseif ($fee->status === 'partial') {
                // Create partial payment (60-80% of fee amount)
                $paymentAmount = $fee->amount * (rand(60, 80) / 100);
                $paymentDate = Carbon::parse($fee->due_date)->subDays(rand(0, 15));
                
                Payment::create([
                    'fee_id' => $fee->id,
                    'amount' => round($paymentAmount, 2),
                    'payment_date' => $paymentDate,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                    'notes' => 'Partial payment',
                    'recorded_by' => $admin->id,
                ]);

                // Sometimes create a second partial payment
                if (rand(0, 1)) {
                    $remainingAmount = $fee->amount - $paymentAmount;
                    $secondPayment = $remainingAmount * (rand(50, 100) / 100);
                    
                    Payment::create([
                        'fee_id' => $fee->id,
                        'amount' => round($secondPayment, 2),
                        'payment_date' => $paymentDate->copy()->addDays(rand(1, 10)),
                        'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                        'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                        'notes' => 'Additional payment',
                        'recorded_by' => $admin->id,
                    ]);
                }
            }

            // Update fee status based on payments
            $fee->updateStatus();
        }
    }
}
