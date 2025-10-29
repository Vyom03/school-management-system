<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::role('teacher')->first();

        $courses = [
            [
                'name' => 'Mathematics 101',
                'code' => 'MATH101',
                'description' => 'Introduction to Algebra and Calculus',
                'teacher_id' => $teacher?->id,
                'semester' => 'Fall 2025',
                'credits' => 3,
            ],
            [
                'name' => 'English Literature',
                'code' => 'ENG201',
                'description' => 'Classic and Contemporary Literature',
                'teacher_id' => $teacher?->id,
                'semester' => 'Fall 2025',
                'credits' => 4,
            ],
            [
                'name' => 'Physics 101',
                'code' => 'PHYS101',
                'description' => 'Fundamentals of Physics',
                'teacher_id' => $teacher?->id,
                'semester' => 'Fall 2025',
                'credits' => 4,
            ],
            [
                'name' => 'History 101',
                'code' => 'HIST101',
                'description' => 'World History Overview',
                'teacher_id' => $teacher?->id,
                'semester' => 'Fall 2025',
                'credits' => 3,
            ],
            [
                'name' => 'Computer Science',
                'code' => 'CS101',
                'description' => 'Introduction to Programming',
                'teacher_id' => $teacher?->id,
                'semester' => 'Fall 2025',
                'credits' => 4,
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}

