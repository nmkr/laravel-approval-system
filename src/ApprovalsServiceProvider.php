<?php

namespace Swatkins\Approvals;

use Illuminate\Support\ServiceProvider;

/**
 * Class ApprovalsServiceProvider
 *
 * @package Swatkins\Approvals
 */
class ApprovalsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {


    }

}
