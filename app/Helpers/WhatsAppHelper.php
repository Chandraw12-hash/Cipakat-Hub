<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class WhatsAppHelper
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('FONNTE_TOKEN');
    }

    public function send($phone, $message)
    {
        $phone = $this->cleanPhoneNumber($phone);

        try {
            $response = $this->client->post('https://api.fonnte.com/send', [
                'headers' => ['Authorization' => $this->apiKey],
                'form_params' => [
                    'target' => $phone,
                    'message' => $message,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function sendWithFile($phone, $message, $filePath)
    {
        $phone = $this->cleanPhoneNumber($phone);

        try {
            $response = $this->client->post('https://api.fonnte.com/send', [
                'headers' => ['Authorization' => $this->apiKey],
                'multipart' => [
                    [
                        'name' => 'target',
                        'contents' => $phone
                    ],
                    [
                        'name' => 'message',
                        'contents' => $message
                    ],
                    [
                        'name' => 'file',
                        'contents' => fopen($filePath, 'r'),
                        'filename' => basename($filePath)
                    ]
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            \Log::error('Send WA with file error: ' . $e->getMessage());
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function cleanPhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (substr($phone, 0, 1) == '0') {
            $phone = '62' . substr($phone, 1);
        }
        return $phone;
    }
}
