<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'instructions',
        'course_id',
        'teacher_id',
        'due_date',
        'max_score',
        'allowed_file_types',
        'max_file_size',
        'is_published',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'max_score' => 'decimal:2',
        'is_published' => 'boolean',
        'max_file_size' => 'integer',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Check if assignment is overdue
     */
    public function getIsOverdueAttribute()
    {
        return $this->due_date < now();
    }

    /**
     * Check if assignment is due soon (within 24 hours)
     */
    public function getIsDueSoonAttribute()
    {
        return $this->due_date > now() && $this->due_date <= now()->addDay();
    }

    /**
     * Get submission count
     */
    public function getSubmissionCountAttribute()
    {
        return $this->submissions()->where('status', '!=', 'draft')->count();
    }

    /**
     * Get graded submission count
     */
    public function getGradedCountAttribute()
    {
        return $this->submissions()->whereIn('status', ['graded', 'returned'])->count();
    }

    /**
     * Scope for published assignments
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for assignments in a course
     */
    public function scopeForCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    /**
     * Scope for assignments by teacher
     */
    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }
}
