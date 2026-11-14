<?php

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Jobs\SendEventSmsJob;

class SendEventReminderSms extends Command
{
    protected $signature = 'events:send-reminders';
    protected $description = 'Send SMS reminders for events approaching their start time';

    public function handle()
    {
        $minutesBefore = 5;
        $now = Carbon::now();

        $this->info("Current time: {$now}");

        $events = Event::whereNotNull('sms_template')
            ->where('sms_sent', false)
            ->get();

        if ($events->isEmpty()) {
            $this->info('No events with sms_template and not sent.');
        }

        foreach ($events as $event) {
            // Safely get the date and time as strings
            $dateStr = $event->event_date instanceof Carbon
                ? $event->event_date->toDateString()
                : $event->event_date;
            $timeStr = $event->start_time instanceof Carbon
                ? $event->start_time->toTimeString()
                : $event->start_time;

            $this->info("Event ID {$event->id}: dateStr='{$dateStr}', timeStr='{$timeStr}'");

            if (empty($timeStr)) {
                $this->info("  -> skipped: no start_time");
                continue;
            }

            try {
                $eventStart = Carbon::parse($dateStr . ' ' . $timeStr);
                $windowStart = $eventStart->copy()->subMinutes($minutesBefore);

                $this->info("  -> windowStart: {$windowStart}, eventStart: {$eventStart}");

                if ($now->between($windowStart, $eventStart)) {
                    SendEventSmsJob::dispatch($event);
                    $this->info("  -> DISPATCHED SMS job for event ID {$event->id}");
                } else {
                    $this->info("  -> not in window");
                }
            } catch (\Exception $e) {
                $this->error("  -> parsing error: " . $e->getMessage());
            }
        }

        $this->info('Reminder check completed.');
    }
}