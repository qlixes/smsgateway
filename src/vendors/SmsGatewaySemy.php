<?php

namespace qlixes\SmsGateway\Vendors;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use qlixes\SmsGateway\SmsGatewayInterface;

class SmsGatewaySemy extends Client implements SmsGatewayInterface
{
    private $device;

    private $token;

    private $options = [];

    function __construct()
    {
        parent::__construct(['base_uri' => config('smsgateway.uri')]);

        $this->token = config('smsgateway.token');

        $config = json_decode(config('smsgateway.device'));
        $this->device = implode(',', $config);

        $this->options['header'] = ['Accept' => 'application/json'];
    }

    function setParams($params = [])
    {
        $query = [];
        $query['token'] = $this->token;

        $query['device'] = $this->device;

        if($params['message'])
            $query['message'] = $params['message'];

        if($params['phone'])
            $query['phone'] = $params['phone'];

        if($params['is_archive'])
            $query['is_arhive'] = $params['is_archive'];

        if($params['list_id'] && is_array($params['list_id']))
            $query['list_id'] = implode(',', $params['list_id']);

        $this->options['query'] = $query;

        return $this;
    }

    function sms(array $destination, String $text)
    {
        $messages = [];
        foreach ($destinations as $destination) {
            $messages[] = [
                'phone_number' => $destination,
                'message'      => $text,
                'device_id'    => $this->device,
            ];
        }

        $response = $this->request('GET', 'sms.php', $this->options);

        if($response->getStatusCode() != 200)
            Log::error($response->getReasonPhrase());

        return [
            'code' => $response->getStatusCode(),
            'message' => $response->getReasonPhrase(),
            'data' => json_decode($response->getBody()->getContents())
        ];
    }
}
