<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use App\Jobs\SendEventSmsJob;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('category')
            ->latest()
            ->paginate(20);

        return view('events.index', compact('events'));
    }

    public function create()
    {
        $categories = EventCategory::where('is_active',1)
            ->whereNull('deleted_at')
            ->get();

        return view('events.create', compact('categories'));
    }

   public function store(Request $request)
{
    $data = $request->validate([
        'event_category_id' => 'required|exists:event_categories,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',

        'event_date' => 'required|date',
        'start_time' => 'nullable',
        'end_time' => 'nullable',

        'location' => 'nullable|string',
        'capacity' => 'nullable|integer',

        'status' => 'required|in:upcoming,ongoing,completed,cancelled,draft',

        /*
        |--------------------------------------------------------------------------
        | SMS Template Field
        |--------------------------------------------------------------------------
        */
        'sms_template' => 'nullable|string'
    ]);

    /*
    |--------------------------------------------------------------------------
    | Create Event
    |--------------------------------------------------------------------------
    */
    $event = Event::create($data);

    /*
    |--------------------------------------------------------------------------
    | Queue SMS Automation
    |--------------------------------------------------------------------------
    */
    if ($event->status === 'upcoming' && $event->sms_template) {
        dispatch(new SendEventSmsJob($event));
    }

    return redirect()
        ->route('events.index')
        ->with('success', 'Event created successfully');
}

    public function edit(Event $event)
    {
        $categories = EventCategory::all();

        return view('events.edit', compact('event','categories'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'event_category_id' => 'required|exists:event_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',

            'event_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',

            'location' => 'nullable|string',
            'capacity' => 'nullable|integer',

            'status' => 'required|in:upcoming,ongoing,completed,cancelled,draft'
        ]);

        $event->update($data);

        return redirect()
            ->route('events.index')
            ->with('success','Event updated successfully');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return back()->with('success','Event deleted');
    }
}