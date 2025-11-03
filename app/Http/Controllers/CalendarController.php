<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Display the calendar view
     */
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));
        
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // Get events for the month
        $events = Event::visibleTo(auth()->user())
            ->inDateRange($startDate->format('Y-m-d'), $endDate->format('Y-m-d'))
            ->get();

        // Format events for calendar display
        $eventsByDate = [];
        foreach ($events as $event) {
            $currentDate = $event->start_date->copy();
            $endEventDate = $event->end_date ?? $event->start_date;
            
            while ($currentDate->lte($endEventDate) && $currentDate->month == $month) {
                $dateKey = $currentDate->format('Y-m-d');
                if (!isset($eventsByDate[$dateKey])) {
                    $eventsByDate[$dateKey] = [];
                }
                $eventsByDate[$dateKey][] = $event;
                $currentDate->addDay();
            }
        }

        return view('calendar.index', compact('year', 'month', 'startDate', 'endDate', 'eventsByDate'));
    }
}
