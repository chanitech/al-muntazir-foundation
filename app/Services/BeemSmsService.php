<?php

namespace App\Services;

use GuzzleHttp\Client;

class BeemSmsService
{
    protected $client;
    protected $apiKey;
    protected $secretKey;
    protected $senderId;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.beem.api_key');
        $this->secretKey = config('services.beem.secret_key');
        $this->senderId = config('services.beem.sender_id');
    }

    public function send($phone, $message)
    {
        $response = $this->client->post(
            'https://apisms.beem.africa/v1/send',
            [
                'auth' => [$this->apiKey, $this->secretKey],
                'json' => [
                    'source_addr' => $this->senderId,
                    'schedule_time' => '',
                    'encoding' => 0,
                    'message' => $message,
                    'recipients' => [
                        ['recipient_id' => 1, 'dest_addr' => $phone]
                    ]
                ]
            ]
        );

        return json_decode($response->getBody(), true);
    }
}