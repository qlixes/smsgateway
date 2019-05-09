<?php

namespace qlixes\SmsGateway;

use Illuminate\Support\ServiceProvider;
use qlixes\SmsGateway\Vendors\SmsGatewayMe;
use qlixes\SmsGateway\Vendors\SmsGatewaySemy;

class SmsGatewayServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([__DIR__.'/config/smsgateway.php' => config_path('smsgateway.php')]);
    }

    public function register()
    {
        $config = config('smsgateway.vendor');

        $vendor = "qlixes\\SmsGateway\\Vendors\\{$config}";

        $this->app->singleton($vendor, function($app) use($vendor){

            return new $vendor();
        });
    }
}
