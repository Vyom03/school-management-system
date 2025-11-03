<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ParentUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'parent_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get all students linked to this parent
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'parent_user_id', 'student_id')
                    ->withPivot('relationship', 'is_primary')
                    ->withTimestamps();
    }

    /**
     * Get primary students (where is_primary = true)
     */
    public function primaryStudents()
    {
        return $this->students()->wherePivot('is_primary', true);
    }
}
