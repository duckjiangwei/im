<?php

namespace DuckMan\Im;

use Illuminate\Support\ServiceProvider;

class ImServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('im', function () {
            return new ServerAPI();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/im.php' => config_path('im.php'),
        ]);
    }
}