<?php

namespace qlixes\SmsGateway;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SmsGateway extends Client
{
    function __construct()
    {
        parent::__construct(['base_uri' => 'https://smsgateway.me/api/v4/']);

        $this->token = config('smsgateway.token');

        $this->headers['Accept'] = 'application/json';
        $this->headers['Authorization'] = $this->token;
    }

    function setDevice(int $id): self
    {
        $this->device = $id ?? config('smsgateway.device');

        return $this;
    }

    function device(int $id): ?array
    {
        $id = $id ?? $this->device;

        $response = $this->request('GET', "device/{$id}", ['headers' => $this->headers]);

        if($response->getStatusCode() != 200)
            Log::error($response->getReasonPhrase());

        return [
            'code' => $response->getStatusCode(),
            'message' => $response->getReasonPhrase(),
            'data' => $response->getBody()
        ];
    }

    function sms(array $destination, string $text): ?array
    {
        $messages = [];
        foreach ($destinations as $destination) {
            $messages[] = [
                'phone_number' => $destination,
                'message'      => $text,
                'device_id'    => $this->device,
            ];
        }

        $response = $this->request('POST', "message/send", ['headers' => $this->headers, 'json' => $messages]);

        if($response->getStatusCode() != 200)
            Log::error($response->getReasonPhrase());

        return [
            'code' => $response->getStatusCode(),
            'message' => $response->getReasonPhrase(),
            'data' => $response->getBody()
        ];
    }
}