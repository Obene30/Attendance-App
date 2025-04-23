<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(Request $request)
{
    $query = Event::query();

    if ($request->filled('search_date')) {
        $query->whereDate('start_time', $request->search_date);
    }

    $events = $query->orderBy('start_time')->paginate(5);

    $calendarEvents = $query->get()->map(function ($event) {
        return [
            'title' => $event->title,
            'start' => $event->start_time,
            'end'   => $event->end_time,
            'description' => $event->description,
        ];
    });

    return view('events.index', compact('events', 'calendarEvents'));
}


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'description' => 'nullable|string',
        ]);

        Event::create([
            'title' => $request->title,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'event_date' => Carbon::parse($request->start_time)->toDateString(), // fallback for compatibility
            'description' => $request->description,
        ]);

        return redirect()->route('events.index')->with('success', 'Event created successfully!');
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'description' => 'nullable|string',
        ]);

        $event->update([
            'title' => $request->title,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'event_date' => Carbon::parse($request->start_time)->toDateString(),
            'description' => $request->description,
        ]);

        return redirect()->route('events.index')->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
    }
}
