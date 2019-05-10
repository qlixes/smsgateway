<?php

namespace qlixes\SmsGateway\Vendors;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SmsGatewaySemy extends Client
{
    private $device;

    private $token;

    private $options = [];

    function __construct()
    {
        parent::__construct(['base_uri' => config('smsgateway.uri')]);

        $this->token = config('smsgateway.token');

        $this->device = config('smsgateway.device');

        $this->options['header'] = ['Accept' => 'application/json'];
        $this->options['verify'] = false;
    }

    function setParams($params = [])
    {
        $query = [];
        $query['token'] = $this->token;

        $query['device'] = $this->device;

        if($params['list_id'] && is_array($params['list_id']))
            $query['list_id'] = implode(',', $params['list_id']);

        $this->options['query'] = $query;

        return $this;
    }

    function sms(array $destinations, String $text)
    {
        $messages = [];
        foreach ($destinations as $destination) {
            $messages = [
                'phone' => $destination,
                'msg' => $text,
                'device' => $this->device,
                'token' => $this->token
            ];
        }

        $this->options['json'] = $messages;

        $response = $this->request('POST', 'sms.php', $this->options);

        if($response->getStatusCode() != 200)
            Log::error($response->getReasonPhrase());

        return [
            'code' => $response->getStatusCode(),
            'message' => $response->getReasonPhrase(),
            'data' => json_decode($response->getBody()->getContents())
        ];
    }
}
