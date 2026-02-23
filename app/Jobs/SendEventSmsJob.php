<?php

namespace App\Jobs;

use App\Models\Event;
use App\Models\Member;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;





class SendEventSmsJob implements ShouldQueue
{
    use Queueable;

    public Event $event;

    /*
    |--------------------------------------------------------------------------
    | Inject Event Object
    |--------------------------------------------------------------------------
    */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /*
    |--------------------------------------------------------------------------
    | Job Execution
    |--------------------------------------------------------------------------
    */
    public function handle(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate SMS Sending
        |--------------------------------------------------------------------------
        */
        if ($this->event->sms_sent) {
            return;
        }

        $members = Member::whereNotNull('phone')->get();

        foreach ($members as $member) {

            Http::withBasicAuth(
                config('services.beem.api_key'),
                config('services.beem.secret_key')
            )->post('https://apisms.beem.africa/v1/send', [
                'source_addr' => config('services.beem.sender_id'),
                'schedule_time' => '',
                'encoding' => 0,
                'message' => "Reminder: {$this->event->title} is happening on {$this->event->event_date}",
                'recipients' => [
                    [
                        'recipient_id' => $member->id ?? 1,
                        'dest_addr' => $member->phone
                    ]
                ]
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Mark SMS Sent
        |--------------------------------------------------------------------------
        */
        $this->event->update([
            'sms_sent' => true,
            'sms_sent_at' => now()
        ]);
    }
}