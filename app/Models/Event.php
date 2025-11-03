<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'type',
        'visibility',
        'course_id',
        'created_by',
        'is_all_day',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_all_day' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function ($subQ) use ($startDate, $endDate) {
                  $subQ->where('start_date', '<=', $startDate)
                       ->where(function ($eQ) use ($endDate) {
                           $eQ->whereNull('end_date')
                              ->orWhere('end_date', '>=', $endDate);
                       });
              });
        });
    }

    /**
     * Scope for filtering by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for visible events based on user role
     */
    public function scopeVisibleTo($query, $user)
    {
        if (!$user) {
            return $query->where('visibility', 'public');
        }

        if ($user->hasRole('admin')) {
            return $query; // Admins see all
        }

        $role = $user->roles->first()?->name ?? 'guest';
        
        return $query->where(function ($q) use ($role) {
            $q->where('visibility', 'public')
              ->orWhere('visibility', $role);
        });
    }

    /**
     * Get formatted date range
     */
    public function getFormattedDateRangeAttribute()
    {
        if ($this->is_all_day) {
            if ($this->end_date && $this->start_date->format('Y-m-d') !== $this->end_date->format('Y-m-d')) {
                return $this->start_date->format('M j') . ' - ' . $this->end_date->format('M j, Y');
            }
            return $this->start_date->format('M j, Y');
        }

        $start = $this->start_date->format('M j, Y');
        if ($this->start_time) {
            $time = \Carbon\Carbon::createFromTimeString($this->start_time);
            $start .= ' ' . $time->format('g:i A');
        }

        if ($this->end_date && $this->start_date->format('Y-m-d') !== $this->end_date->format('Y-m-d')) {
            $end = $this->end_date->format('M j, Y');
            if ($this->end_time) {
                $time = \Carbon\Carbon::createFromTimeString($this->end_time);
                $end .= ' ' . $time->format('g:i A');
            }
            return $start . ' - ' . $end;
        }

        if ($this->end_time) {
            $time = \Carbon\Carbon::createFromTimeString($this->end_time);
            $start .= ' - ' . $time->format('g:i A');
        }

        return $start;
    }
}
