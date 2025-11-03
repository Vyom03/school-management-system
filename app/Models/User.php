<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'grade_level',
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
    ];

    public function enrollments()
    {
        return $this->hasMany(\App\Models\Enrollment::class, 'student_id');
    }

    public function teachingCourses()
    {
        return $this->hasMany(\App\Models\Course::class, 'teacher_id');
    }

    /**
     * Get fees for this student
     */
    public function fees()
    {
        return $this->hasMany(Fee::class, 'student_id');
    }

    /**
     * Get the grade level label
     */
    public function getGradeLevelLabelAttribute()
    {
        return $this->grade_level ? "Grade {$this->grade_level}" : 'Not Assigned';
    }

    /**
     * Get available grade levels
     */
    public static function getAvailableGrades()
    {
        return [
            1 => 'Grade 1',
            2 => 'Grade 2',
            3 => 'Grade 3',
            4 => 'Grade 4',
            5 => 'Grade 5',
            6 => 'Grade 6',
            7 => 'Grade 7',
            8 => 'Grade 8',
            9 => 'Grade 9',
            10 => 'Grade 10',
            11 => 'Grade 11',
            12 => 'Grade 12',
        ];
    }
}
