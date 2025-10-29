<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;
use App\Models\Course;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::role('admin')->first();
        $teacher = User::role('teacher')->first();
        $course = Course::first();

        $announcements = [
            [
                'title' => 'Welcome to the New Academic Year 2024-2025',
                'content' => 'Dear Students and Faculty,

We are excited to welcome you to the new academic year! This year promises to bring new opportunities for learning, growth, and achievement. We look forward to working with all of you to make this year successful.

Please review your schedules and ensure all course registrations are completed by the end of this week.

Best regards,
Administration',
                'category' => 'general',
                'audience' => 'all',
                'is_pinned' => true,
                'created_by' => $admin->id,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Mid-Term Examinations Schedule Released',
                'content' => 'The mid-term examination schedule has been published. Please check your student portal for detailed dates and times. All exams will be conducted in-person unless otherwise specified.

Study materials and past papers are available in the library and online portal.',
                'category' => 'academic',
                'audience' => 'students',
                'is_pinned' => true,
                'created_by' => $admin->id,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Annual Sports Day - Registration Open',
                'content' => 'We are thrilled to announce our Annual Sports Day scheduled for next month! Registration is now open for all sporting events including track and field, basketball, volleyball, and more.

Sign up at the Physical Education office or through the online portal. Don\'t miss this opportunity to showcase your athletic talents!',
                'category' => 'event',
                'audience' => 'all',
                'is_pinned' => false,
                'created_by' => $admin->id,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Library Hours Extended During Exam Week',
                'content' => 'To support students during the upcoming examination period, the library will extend its hours from 7:00 AM to 11:00 PM, Monday through Saturday.

Additional study rooms have been reserved. Please book in advance through the library portal.',
                'category' => 'general',
                'audience' => 'students',
                'is_pinned' => false,
                'created_by' => $admin->id,
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Faculty Meeting - This Friday',
                'content' => 'All faculty members are requested to attend the monthly faculty meeting this Friday at 2:00 PM in the main conference room.

Agenda includes curriculum updates, student performance review, and upcoming events planning.',
                'category' => 'general',
                'audience' => 'teachers',
                'is_pinned' => false,
                'created_by' => $admin->id,
                'published_at' => now()->subHours(12),
            ],
            [
                'title' => 'URGENT: Campus Maintenance - Building B Closed Tomorrow',
                'content' => 'Due to emergency maintenance work, Building B will be closed tomorrow (entire day). All classes scheduled in Building B have been relocated to Building A.

Check your email for updated classroom assignments. We apologize for any inconvenience.',
                'category' => 'urgent',
                'audience' => 'all',
                'is_pinned' => true,
                'created_by' => $admin->id,
                'published_at' => now()->subHours(2),
            ],
            [
                'title' => 'Guest Lecture on Artificial Intelligence',
                'content' => 'Join us for an exciting guest lecture on "The Future of Artificial Intelligence" by Dr. Sarah Johnson, renowned AI researcher.

Date: Next Tuesday, 10:00 AM
Venue: Main Auditorium
Open to all students and faculty.',
                'category' => 'event',
                'audience' => 'all',
                'is_pinned' => false,
                'created_by' => $teacher->id,
                'published_at' => now()->subHours(6),
            ],
        ];

        // Add course-specific announcement if course exists
        if ($course) {
            $announcements[] = [
                'title' => 'Assignment Due Date Extended',
                'content' => 'The assignment originally due this Friday has been extended to next Monday to give you more time to complete quality work.

Please submit through the online portal. Late submissions will not be accepted without prior approval.',
                'category' => 'academic',
                'audience' => 'students',
                'course_id' => $course->id,
                'is_pinned' => false,
                'created_by' => $teacher->id,
                'published_at' => now()->subHours(3),
            ];
        }

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}
