<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use App\Jobs\SendEventSmsJob;
use App\Services\Website\ImageUploader;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('category')->latest()->paginate(20);
        return view('events.index', compact('events'));
    }

    public function create()
    {
        $categories = EventCategory::where('is_active', 1)->whereNull('deleted_at')->get();
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
            'status' => 'required|in:'.implode(',', array_keys(Event::STATUSES)),
            'sms_template' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // Public website: visibility switch and optional cover image
        $data['is_public'] = $request->boolean('is_public');
        unset($data['image']);
        if ($request->hasFile('image')) {
            $data['image'] = app(ImageUploader::class)->store($request->file('image'), 'events', 1600);
        }

        // Create the event
        $event = Event::create($data);

        // No longer dispatch the job immediately.
        // The scheduled command (events:send-reminders) will send the SMS when the start time approaches.

        return redirect()
            ->route('events.index')
            ->with('success', 'Event created successfully. SMS will be sent before the event start time.');
    }

    public function edit(Event $event)
    {
        $categories = EventCategory::all();
        return view('events.edit', compact('event', 'categories'));
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
            'status' => 'required|in:'.implode(',', array_keys(Event::STATUSES)),
            'sms_template' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // Public website: visibility switch and optional cover image
        $uploader = app(ImageUploader::class);
        $oldImage = null;
        $data['is_public'] = $request->boolean('is_public');
        unset($data['image']);
        if ($request->hasFile('image')) {
            $data['image'] = $uploader->store($request->file('image'), 'events', 1600);
            $oldImage = $event->image;
        } elseif ($request->boolean('remove_image')) {
            $data['image'] = null;
            $oldImage = $event->image;
        }

        $event->update($data);
        $uploader->delete($oldImage);

        // If the SMS template is present, reset the sent flag so the command can send it again
        if (!empty($data['sms_template'])) {
            $event->update(['sms_sent' => false]);
        }

        return redirect()->route('events.index')->with('success', 'Event updated successfully');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Event deleted');
    }
}