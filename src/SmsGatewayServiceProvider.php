<?php

namespace qlixes\SmsGateway;

use Illuminate\Support\ServiceProvider;

class SmsGatewayServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([__DIR__.'/config/smsgateway.php' => config_path('smsgateway.php')]);
    }

    public function register()
    {
        $className = config('smsgateway.vendor');

        $classPath = '\qlixes\SmsGateway\Vendors\\'.$className;

        if (!class_exists($classPath)) {
            abort(500, sprintf(
                'vendor %s is not available.',
                $className
            ));
        }

        $this->app->singleton(strtolower($classPath), function($app) use($classPath){
            return new $classPath();
        });
    }
}
