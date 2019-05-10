<?php

namespace qlixes\SmsGateway\Vendors;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SmsGatewayMe extends Client
{
    private $device;

    private $token;

    function __construct()
    {
        parent::__construct(['base_uri' => config('smsgateway.uri')]);

        $this->token = config('smsgateway.token');
        $this->device = config('smsgateway.device');

        $this->headers['Accept'] = 'application/json';
        $this->headers['Content-Type'] = 'application/json';
        $this->headers['Authorization'] = $this->token;

        $this->options['verify'] = false;
    }

    function setDevice(int $id): self
    {
        $this->device = $id ?? config('smsgateway.device');

        return $this;
    }

    function device(int $id = null): ?array
    {
        $id = $id ?? $this->device;

        $response = $this->request('GET', "device/{$id}", ['headers' => $this->headers]);

        if($response->getStatusCode() != 200)
            Log::error($response->getReasonPhrase());

        return [
            'code' => $response->getStatusCode(),
            'message' => $response->getReasonPhrase(),
            'data' => json_decode($response->getBody()->getContents())
        ];
    }

    function sms(array $destinations, string $text): ?array
    {
        $messages = [];
        foreach ($destinations as $destination) {
            $messages[] = [
                'phone_number' => $destination,
                'message'      => $text,
                'device_id'    => $this->device,
            ];
        };

        $response = $this->request('POST', "message/send", ['headers' => $this->headers, 'json' => $messages]);

        if($response->getStatusCode() != 200)
            Log::error($response->getReasonPhrase());

        return [
            'code' => $response->getStatusCode(),
            'message' => $response->getReasonPhrase(),
            'data' => json_decode($response->getBody()->getContents())
        ];
    }
}
