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

    function sms(array $destination, String $text)
    {
        $messages['data'] = [];
        foreach ($destinations as $destination) {
            $messages[] = [
                'phoner' => $destination,
                'msg' => $text,
                'device' => $this->device,
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
