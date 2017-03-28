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

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->publishes([ __DIR__ . '/Config/approvals.php' => config_path('approvals.php') ]);

        $this->loadViewsFrom(__DIR__.'/Views', 'approvals');

        $this->publishes([ __DIR__ . '/Views' => resource_path('views/vendor/approvals') ]);

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom(__DIR__ . '/Config/approvals.php', 'approvals');

    }

}
