<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Common password for all test accounts
        $password = Hash::make('password');

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin One',
            'email' => 'admin1@test.com',
            'password' => $password,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Create Teacher Users
        $teacher1 = User::create([
            'name' => 'Teacher One',
            'email' => 'teacher1@test.com',
            'password' => $password,
            'email_verified_at' => now(),
        ]);
        $teacher1->assignRole('teacher');

        $teacher2 = User::create([
            'name' => 'Teacher Two',
            'email' => 'teacher2@test.com',
            'password' => $password,
            'email_verified_at' => now(),
        ]);
        $teacher2->assignRole('teacher');

        // Create Student Users
        // Grade 8 students
        $student1 = User::create([
            'name' => 'Student One',
            'email' => 'student1@test.com',
            'password' => $password,
            'email_verified_at' => now(),
            'grade_level' => 8,
        ]);
        $student1->assignRole('student');

        $student3 = User::create([
            'name' => 'Student Three',
            'email' => 'student3@test.com',
            'password' => $password,
            'email_verified_at' => now(),
            'grade_level' => 8,
        ]);
        $student3->assignRole('student');

        $student5 = User::create([
            'name' => 'Student Five',
            'email' => 'student5@test.com',
            'password' => $password,
            'email_verified_at' => now(),
            'grade_level' => 8,
        ]);
        $student5->assignRole('student');

        // Grade 10 students
        $student2 = User::create([
            'name' => 'Student Two',
            'email' => 'student2@test.com',
            'password' => $password,
            'email_verified_at' => now(),
            'grade_level' => 10,
        ]);
        $student2->assignRole('student');

        $student4 = User::create([
            'name' => 'Student Four',
            'email' => 'student4@test.com',
            'password' => $password,
            'email_verified_at' => now(),
            'grade_level' => 10,
        ]);
        $student4->assignRole('student');

        // Create additional test user (Grade 10)
        $test = User::create([
            'name' => 'Test One',
            'email' => 'test1@test.com',
            'password' => $password,
            'email_verified_at' => now(),
            'grade_level' => 10,
        ]);
        $test->assignRole('student');
    }
}
