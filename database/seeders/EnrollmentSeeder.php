<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use App\Models\Grade;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::role('student')->get();
        $courses = Course::all();
        $teacher = User::role('teacher')->first();

        // Enroll each student in 2-4 random courses
        foreach ($students as $student) {
            $randomCourses = $courses->random(rand(2, 4));
            
            foreach ($randomCourses as $course) {
                $enrollment = Enrollment::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'status' => 'active',
                ]);

                // Create 3-5 sample grades for each enrollment
                $assignments = [
                    'Midterm Exam',
                    'Final Exam',
                    'Homework 1',
                    'Quiz 1',
                    'Project',
                ];

                $numGrades = rand(3, 5);
                for ($i = 0; $i < $numGrades; $i++) {
                    Grade::create([
                        'enrollment_id' => $enrollment->id,
                        'assignment_name' => $assignments[$i],
                        'score' => rand(60, 100),
                        'max_score' => 100,
                        'comments' => rand(0, 1) ? 'Good work!' : null,
                        'graded_by' => $teacher?->id,
                    ]);
                }
            }
        }
    }
}

