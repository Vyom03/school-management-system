<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $audience = $user->hasRole('student') ? 'students' : ($user->hasRole('teacher') ? 'teachers' : 'all');

        $announcements = Announcement::with(['creator', 'course'])
            ->published()
            ->forAudience($audience)
            ->latest('published_at')
            ->paginate(10);

        $pinnedAnnouncements = Announcement::with(['creator', 'course'])
            ->published()
            ->pinned()
            ->forAudience($audience)
            ->latest('published_at')
            ->get();

        return view('announcements.index', compact('announcements', 'pinnedAnnouncements'));
    }

    public function show(Announcement $announcement)
    {
        // Check if user can view this announcement
        if (!$announcement->isPublished()) {
            abort(404);
        }

        $user = auth()->user();
        $audience = $user->hasRole('student') ? 'students' : ($user->hasRole('teacher') ? 'teachers' : 'all');

        if ($announcement->audience !== 'all' && $announcement->audience !== $audience) {
            abort(403);
        }

        return view('announcements.show', compact('announcement'));
    }
}
