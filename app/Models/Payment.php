<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_id',
        'amount',
        'payment_date',
        'payment_method',
        'transaction_id',
        'notes',
        'receipt_number',
        'recorded_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->receipt_number)) {
                $payment->receipt_number = 'REC-' . strtoupper(Str::random(8));
            }
        });

        static::saved(function ($payment) {
            $payment->fee->updateStatus();
        });

        static::deleted(function ($payment) {
            $payment->fee->updateStatus();
        });
    }

    public function fee(): BelongsTo
    {
        return $this->belongsTo(Fee::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Get formatted payment method label
     */
    public function getPaymentMethodLabelAttribute()
    {
        return match($this->payment_method) {
            'cash' => 'Cash',
            'bank_transfer' => 'Bank Transfer',
            'check' => 'Check',
            'online' => 'Online Payment',
            'card' => 'Card',
            'other' => 'Other',
            default => ucfirst(str_replace('_', ' ', $this->payment_method)),
        };
    }
}
