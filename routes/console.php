<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Artisan Commands
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
*/

// Schedule the new event reminder command to run every minute
// This will send SMS only when an event's start time is approaching
Schedule::command('events:send-reminders')->everyMinute();

// If you still need the daily command, keep it (but ensure it doesn't conflict)
// Schedule::command('event:reminder')->dailyAt('07:00');