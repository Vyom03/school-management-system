<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'amount',
        'frequency',
        'grade_level',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class);
    }

    /**
     * Get formatted frequency label
     */
    public function getFrequencyLabelAttribute()
    {
        return match($this->frequency) {
            'one_time' => 'One Time',
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'semester' => 'Semester',
            'yearly' => 'Yearly',
            default => ucfirst($this->frequency),
        };
    }

    /**
     * Scope for active fee structures
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for grade level
     */
    public function scopeForGrade($query, $gradeLevel)
    {
        return $query->where(function($q) use ($gradeLevel) {
            $q->whereNull('grade_level')
              ->orWhere('grade_level', $gradeLevel);
        });
    }
}
