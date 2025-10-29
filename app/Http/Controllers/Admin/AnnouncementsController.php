<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Course;
use Illuminate\Http\Request;

class AnnouncementsController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with(['creator', 'course'])
            ->latest()
            ->paginate(15);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        $courses = Course::all();
        return view('admin.announcements.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:general,academic,event,urgent',
            'audience' => 'required|in:all,students,teachers',
            'course_id' => 'nullable|exists:courses,id',
            'is_pinned' => 'boolean',
            'publish_now' => 'boolean',
        ]);

        $announcement = new Announcement($validated);
        $announcement->created_by = auth()->id();
        $announcement->is_pinned = $request->has('is_pinned');
        
        if ($request->has('publish_now')) {
            $announcement->published_at = now();
        }

        $announcement->save();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully!');
    }

    public function edit(Announcement $announcement)
    {
        $courses = Course::all();
        return view('admin.announcements.edit', compact('announcement', 'courses'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:general,academic,event,urgent',
            'audience' => 'required|in:all,students,teachers',
            'course_id' => 'nullable|exists:courses,id',
            'is_pinned' => 'boolean',
        ]);

        $announcement->fill($validated);
        $announcement->is_pinned = $request->has('is_pinned');
        
        if ($request->has('publish_now') && !$announcement->published_at) {
            $announcement->published_at = now();
        }

        $announcement->save();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        
        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }
}
