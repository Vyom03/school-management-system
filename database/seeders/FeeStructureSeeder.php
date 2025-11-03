<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeeStructure;

class FeeStructureSeeder extends Seeder
{
    public function run(): void
    {
        $feeStructures = [
            [
                'name' => 'Tuition Fee',
                'description' => 'Annual tuition fee',
                'amount' => 5000.00,
                'frequency' => 'yearly',
                'grade_level' => null, // Applies to all grades
                'is_active' => true,
            ],
            [
                'name' => 'Monthly Tuition',
                'description' => 'Monthly tuition payment',
                'amount' => 500.00,
                'frequency' => 'monthly',
                'grade_level' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Library Fee',
                'description' => 'Library access and resource fee',
                'amount' => 150.00,
                'frequency' => 'semester',
                'grade_level' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Sports Fee',
                'description' => 'Sports activities and equipment fee',
                'amount' => 200.00,
                'frequency' => 'yearly',
                'grade_level' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Lab Fee',
                'description' => 'Science laboratory fee',
                'amount' => 300.00,
                'frequency' => 'semester',
                'grade_level' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Registration Fee',
                'description' => 'One-time registration fee',
                'amount' => 250.00,
                'frequency' => 'one_time',
                'grade_level' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Technology Fee',
                'description' => 'Computer and technology resources fee',
                'amount' => 100.00,
                'frequency' => 'semester',
                'grade_level' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Activity Fee',
                'description' => 'Extracurricular activities fee',
                'amount' => 175.00,
                'frequency' => 'yearly',
                'grade_level' => null,
                'is_active' => true,
            ],
        ];

        foreach ($feeStructures as $structure) {
            FeeStructure::create($structure);
        }
    }
}
