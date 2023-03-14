<?php

namespace Yigit\Larasut;

use Validator;
use Illuminate\Support\ServiceProvider;
use Yigit\Larasut\Providers\ConfigServiceProvider;

class LarasutServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootValidator();
    }

    protected function bootValidator()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(ConfigServiceProvider::class);
    }
}
