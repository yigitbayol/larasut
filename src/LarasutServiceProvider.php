<?php

namespace Yigit\Larasut;

use Validator;
use Illuminate\Support\ServiceProvider;

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
        $this->mergeConfigFrom(__DIR__ . '/Config/larasut.php', 'larasut');
    }
}
