<?php

namespace qlixes\SmsGateway;

interface SmsGatewayInterface
{
    function sms(array $destinations, string $text);
}
