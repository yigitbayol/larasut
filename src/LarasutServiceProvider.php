<?php

namespace Yigitbayol\Larasut;

use Illuminate\Support\ServiceProvider;
use Yigitbayol\Larasut\Providers\ConfigServiceProvider;

class LarasutServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

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
