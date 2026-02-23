<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Event;
use App\Jobs\SendEventSmsJob;
use Carbon\Carbon;

class SendEventReminderSms extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminder-sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    

public function handle()
{
    $today = Carbon::today();

    $events = Event::whereDate('event_date', $today)
                    ->where('sms_sent', false)
                    ->get();

    foreach ($events as $event) {
        SendEventSmsJob::dispatch($event);
        $event->update([
            'sms_sent' => true,
            'sms_sent_at' => now(),
        ]);
    }

    $this->info('Event reminders dispatched.');
}
}
