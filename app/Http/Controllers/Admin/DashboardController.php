<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Event;
use App\Models\Document;
use App\Models\Election;

class DashboardController extends Controller
{
    public function index() {
    return view('dashboard', [
        'membersCount' => \App\Models\Member::count(),
        'eventsCount' => \App\Models\Event::count(),
        'documentsCount' => \App\Models\Document::count(),
        'electionsCount' => \App\Models\Election::count(),
        'latestMembers' => \App\Models\Member::latest()->take(5)->get(),
    ]);
}
}