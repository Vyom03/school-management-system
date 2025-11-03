<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'content',
        'submitted_at',
        'status',
        'score',
        'feedback',
        'graded_at',
        'graded_by',
        'viewed_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'viewed_at' => 'datetime',
        'score' => 'decimal:2',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function files(): HasMany
    {
        return $this->hasMany(SubmissionFile::class);
    }

    /**
     * Check if submission is late
     */
    public function getIsLateAttribute()
    {
        if (!$this->submitted_at || !$this->assignment) {
            return false;
        }
        return $this->submitted_at->gt($this->assignment->due_date);
    }

    /**
     * Get percentage score
     */
    public function getPercentageAttribute()
    {
        if (!$this->score || !$this->assignment || !$this->assignment->max_score) {
            return null;
        }
        return ($this->score / $this->assignment->max_score) * 100;
    }

    /**
     * Get letter grade
     */
    public function getLetterGradeAttribute()
    {
        $percentage = $this->percentage;
        if ($percentage === null) {
            return null;
        }
        
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'F';
    }

    /**
     * Scope for submitted submissions
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', '!=', 'draft')->whereNotNull('submitted_at');
    }

    /**
     * Scope for graded submissions
     */
    public function scopeGraded($query)
    {
        return $query->whereIn('status', ['graded', 'returned']);
    }
}
