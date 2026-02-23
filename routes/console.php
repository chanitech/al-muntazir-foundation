<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Event;
use App\Models\Member;
use App\Services\BeemSmsService;
use App\Console\Commands\SendEventReminderSms;




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

Schedule::call(function () {

    $tomorrow = now()->addDay()->toDateString();

    $events = Event::whereDate('event_date', $tomorrow)
        ->where('status', 'upcoming')
        ->get();

    if ($events->isEmpty()) {
        return;
    }

    $members = Member::where('status', 'Active')->get();

    $sms = app(BeemSmsService::class);

    foreach ($events as $event) {
        foreach ($members as $member) {

            if (!$member->phone) {
                continue;
            }

            $message = "Reminder: {$event->title} is tomorrow at {$event->location}.";

            $sms->send($member->phone, $message);
        }
    }

})->everyMinute();

Schedule::command('event:reminder')
        ->dailyAt('07:00');