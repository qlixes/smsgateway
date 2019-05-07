<?php

namespace qlixes\SmsGateway;


use Illuminate\Support\ServiceProvider;

class SmsGatewayServiceProvider extends ServiceProvider
{

    protected $email;
    protected $password;

    public function boot()
    {
        $this->publishes([__DIR__.'/config/smsgateway.php' => config_path('smsgateway.php')]);
    }

    public function register()
    {
        $this->app->singleton('smsgateway', function($app){

            return new SmsGateway();
        });
    }
}
