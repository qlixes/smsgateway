<?php
namespace qlixes\SmsGateway;

use Illuminate\Support\Facades\Facade as BaseFacace;

class Facade extends BaseFacace
{
    protected static function getFacadeAccessor()
    {
        $vendor = config('smsgateway.vendor');
        return strtolower($vendor);
    }
}
