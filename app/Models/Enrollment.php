<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'status',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function averageGrade()
    {
        $grades = $this->grades;
        if ($grades->count() === 0) {
            return 0;
        }
        
        $totalWeightedScore = 0;
        $totalMaxScore = 0;
        
        foreach ($grades as $grade) {
            $totalWeightedScore += $grade->score;
            $totalMaxScore += $grade->max_score;
        }
        
        if ($totalMaxScore == 0) {
            return 0;
        }
        
        return ($totalWeightedScore / $totalMaxScore) * 100;
    }

    public function letterGrade()
    {
        $average = $this->averageGrade();
        
        if ($average >= 90) return 'A';
        if ($average >= 80) return 'B';
        if ($average >= 70) return 'C';
        if ($average >= 60) return 'D';
        return 'F';
    }

    public function attendancePercentage()
    {
        $total = $this->attendances()->count();
        if ($total === 0) return 0;
        
        $present = $this->attendances()->whereIn('status', ['present', 'late'])->count();
        return ($present / $total) * 100;
    }

    public function attendanceStats()
    {
        return [
            'total' => $this->attendances()->count(),
            'present' => $this->attendances()->where('status', 'present')->count(),
            'absent' => $this->attendances()->where('status', 'absent')->count(),
            'late' => $this->attendances()->where('status', 'late')->count(),
            'excused' => $this->attendances()->where('status', 'excused')->count(),
            'percentage' => round($this->attendancePercentage(), 2),
        ];
    }
}
