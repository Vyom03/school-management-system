<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Course;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::with(['creator', 'course'])
            ->latest('start_date')
            ->paginate(20);

        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::all();
        return view('admin.events.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'type' => 'required|in:academic,holiday,event,exam,meeting',
            'visibility' => 'required|in:public,students,teachers,admin',
            'course_id' => 'nullable|exists:courses,id',
            'is_all_day' => 'boolean',
        ]);

        $event = new Event($validated);
        $event->created_by = auth()->id();
        $event->is_all_day = $request->has('is_all_day');
        
        // If all day, set end_date to start_date if not provided
        if ($event->is_all_day && !$request->has('end_date')) {
            $event->end_date = $event->start_date;
        }

        $event->save();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load(['creator', 'course']);
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $courses = Course::all();
        return view('admin.events.edit', compact('event', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'type' => 'required|in:academic,holiday,event,exam,meeting',
            'visibility' => 'required|in:public,students,teachers,admin',
            'course_id' => 'nullable|exists:courses,id',
            'is_all_day' => 'boolean',
        ]);

        $event->fill($validated);
        $event->is_all_day = $request->has('is_all_day');
        
        // If all day, clear times
        if ($event->is_all_day) {
            $event->start_time = null;
            $event->end_time = null;
            if (!$request->has('end_date')) {
                $event->end_date = $event->start_date;
            }
        }

        $event->save();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }
}
