<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Event;
use App\Jobs\SendEventSmsJob;

class EventAutoSmsCommand extends Command
{
    protected $signature = 'event:auto-sms';

    protected $description = 'Send SMS when event starts';

    public function handle()
    {
        $events = Event::where('status','upcoming')
            ->where('sms_sent',0)
            ->get();

        foreach ($events as $event) {

            $eventStart = Carbon::parse(
                $event->event_date.' '.$event->start_time
            );

            /*
            If event start time reached
            */
            if (now()->gte($eventStart)) {

                if($event->sms_template){

                    dispatch(new SendEventSmsJob($event));

                    $event->update([
                        'status' => 'ongoing',
                        'sms_sent' => 1,
                        'sms_sent_at' => now()
                    ]);
                }
            }
        }
    }
}