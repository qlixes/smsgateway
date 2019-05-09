<?php

namespace qlixes\SmsGateway;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SemySms extends Client
{
    private $device;

    private $token;

    private $options = [];

    function __construct()
    {
        parent::__construct(['base_uri' => config('smeysms.uri')]);

        $this->token = config('smeysms.token');
        $this->device = config('smeysms.device');

        $this->headers['Accept'] = 'application/json';
    }

    function setDevice(int $id): self
    {
        $this->device = $id ?? config('semysms.device');

        return $this;
    }

    function setParams($params = [])
    {
        $query = [];
        $query['token'] = $this->token;

        if($params['device'] && is_array($params['device']))
            $query['device'] = implode(',', $params['device']);

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

    function s
}
