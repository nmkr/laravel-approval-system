<?php

namespace Swatkins\Approvals;

use Swatkins\Approvals\Models\Review;
use Swatkins\Approvals\Models\Approval;
use Illuminate\Support\ServiceProvider;
use Swatkins\Approvals\Observers\ReviewObserver;
use Swatkins\Approvals\Observers\ApprovalObserver;

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

        $this->publishes([ __DIR__ . '/Config/approvals.php' => config_path('approvals.php') ]);

        Review::observe(ReviewObserver::class);
        Approval::observe(ApprovalObserver::class);

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
