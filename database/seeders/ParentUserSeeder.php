<?php

namespace Database\Seeders;

use App\Models\ParentUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ParentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create parent users and link them to students
        $students = User::role('student')->take(5)->get();

        if ($students->count() > 0) {
            // Create parent for first student
            $parent1 = ParentUser::create([
                'name' => 'John Parent',
                'email' => 'parent1@test.com',
                'password' => Hash::make('password'),
                'phone' => '+1234567890',
                'address' => '123 Main Street, City',
            ]);

            $parent1->students()->attach($students[0]->id, [
                'relationship' => 'parent',
                'is_primary' => true,
            ]);

            // Create parent for second student
            if ($students->count() > 1) {
                $parent2 = ParentUser::create([
                    'name' => 'Jane Guardian',
                    'email' => 'parent2@test.com',
                    'password' => Hash::make('password'),
                    'phone' => '+1234567891',
                    'address' => '456 Oak Avenue, City',
                ]);

                $parent2->students()->attach($students[1]->id, [
                    'relationship' => 'guardian',
                    'is_primary' => true,
                ]);
            }

            // Create a parent with multiple children
            if ($students->count() > 2) {
                $parent3 = ParentUser::create([
                    'name' => 'Michael Smith',
                    'email' => 'parent3@test.com',
                    'password' => Hash::make('password'),
                    'phone' => '+1234567892',
                    'address' => '789 Pine Road, City',
                ]);

                $parent3->students()->attach($students[2]->id, [
                    'relationship' => 'parent',
                    'is_primary' => true,
                ]);

                if ($students->count() > 3) {
                    $parent3->students()->attach($students[3]->id, [
                        'relationship' => 'parent',
                        'is_primary' => false,
                    ]);
                }
            }
        }
    }
}
