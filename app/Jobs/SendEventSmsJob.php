<?php

namespace App\Jobs;

use App\Models\Event;
use App\Models\Member;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;

class SendEventSmsJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels, Dispatchable;

    public Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function handle(): void
    {
        if ($this->event->sms_sent || !$this->event->sms_template) {
            return;
        }

        $members = Member::whereNotNull('phone')->get();

        foreach ($members as $member) {

            $message = $this->parseTemplate(
                $this->event->sms_template,
                $this->event,
                $member
            );

            Http::withBasicAuth(
                config('services.beem.api_key'),
                config('services.beem.secret_key')
            )->post('https://apisms.beem.africa/v1/send', [
                'source_addr' => config('services.beem.sender_id'),
                'encoding' => 0,
                'message' => $message,
                'recipients' => [
                    [
                        'recipient_id' => $member->id,
                        'dest_addr' => $member->phone
                    ]
                ]
            ]);
        }

        $this->event->update([
            'sms_sent' => true,
            'sms_sent_at' => now()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Template Parser
    |--------------------------------------------------------------------------
    */
    private function parseTemplate(string $template, Event $event, Member $member): string
    {
        return str_replace(
            [
                '{title}',
                '{event_date}',
                '{location}',
                '{member_name}'
            ],
            [
                $event->title ?? '',
                $event->event_date ?? '',
                $event->location ?? '',
                $member->name ?? ''
            ],
            $template
        );
    }
}