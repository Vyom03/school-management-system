<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enrollment;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $enrollments = Enrollment::all();
        
        // Generate attendance for the last 30 days
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        foreach ($enrollments as $enrollment) {
            $currentDate = $startDate->copy();
            
            while ($currentDate->lte($endDate)) {
                // Skip weekends
                if (!$currentDate->isWeekend()) {
                    // 85% chance of being present, 10% absent, 3% late, 2% excused
                    $rand = rand(1, 100);
                    
                    if ($rand <= 85) {
                        $status = 'present';
                    } elseif ($rand <= 95) {
                        $status = 'absent';
                    } elseif ($rand <= 98) {
                        $status = 'late';
                    } else {
                        $status = 'excused';
                    }

                    Attendance::create([
                        'enrollment_id' => $enrollment->id,
                        'date' => $currentDate->toDateString(),
                        'status' => $status,
                        'notes' => $status === 'excused' ? 'Medical appointment' : ($status === 'late' ? 'Traffic' : null),
                        'marked_by' => $enrollment->course->teacher_id,
                    ]);
                }
                
                $currentDate->addDay();
            }
        }
    }
}
