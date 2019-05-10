<?php

namespace qlixes\SmsGateway;

use Illuminate\Support\ServiceProvider;

use qlixes\SmsGateway\Vendors\SmsGatewayMe;
use qlixes\SmsGateway\Vendors\SmsGatewaySemy;

class SmsGatewayServiceProvider extends ServiceProvider
{
    var $vendor;

    public function boot()
    {
        $this->publishes([__DIR__.'/config/smsgateway.php' => config_path('smsgateway.php')]);

        $this->vendor = config('smsgateway.vendor');
    }

    public function register()
    {
        $this->app->singleton('smsgateway', function($app) {
            $alias = sprintf('\qlixes\SmsGateway\Vendors\%s',  $this->vendor);
            return new $alias();
        });
    }
}
