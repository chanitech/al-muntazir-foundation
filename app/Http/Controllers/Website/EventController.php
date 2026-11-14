<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\View\View;

/** Events that have "Show on public website" ticked in Admin → Events. */
class EventController extends Controller
{
    public function index(): View
    {
        return view('website.events.index', [
            'upcoming' => Event::publicListing()->upcoming()->with('category')->get(),
            'past' => Event::publicListing()->past()->paginate(9),
        ]);
    }

    public function show(Event $event): View
    {
        abort_unless($event->isVisibleOnWebsite(), 404);

        return view('website.events.show', [
            'event' => $event->loadMissing('category'),
            'more' => Event::publicListing()->upcoming()->whereKeyNot($event->id)->take(3)->get(),
        ]);
    }
}
