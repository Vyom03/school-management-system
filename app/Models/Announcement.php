<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category',
        'audience',
        'course_id',
        'is_pinned',
        'created_by',
        'published_at',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'published_at' => 'datetime',
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
     * Scope for published announcements
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope for pinned announcements
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Scope for audience-specific announcements
     */
    public function scopeForAudience($query, $audience)
    {
        return $query->where(function ($q) use ($audience) {
            $q->where('audience', 'all')
              ->orWhere('audience', $audience);
        });
    }

    /**
     * Check if announcement is published
     */
    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at <= now();
    }
}
