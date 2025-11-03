<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ParentRegistrationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'code',
        'email',
        'relationship',
        'used',
        'expires_at',
        'created_by',
        'used_at',
    ];

    protected $casts = [
        'used' => 'boolean',
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    /**
     * Generate a unique registration code
     */
    public static function generateCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Check if code is valid (not used and not expired)
     */
    public function isValid(): bool
    {
        if ($this->used) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Mark code as used
     */
    public function markAsUsed(): void
    {
        $this->update([
            'used' => true,
            'used_at' => now(),
        ]);
    }

    /**
     * Get the student this code is for
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the admin who created this code
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
