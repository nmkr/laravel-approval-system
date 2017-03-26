<?php

namespace Tests;

use Orchestra\Testbench\Http\Kernel;
use Orchestra\Database\ConsoleServiceProvider;
use \Orchestra\Testbench\TestCase as OrchestraTestCase;
use Swatkins\Approvals\ApprovalsServiceProvider;
use Swatkins\LaravelGithubReleases\GithubReleasesServiceProvider;
use Swatkins\LaravelGithubReleases\ReleaseManager;

abstract class BaseTestCase extends OrchestraTestCase
{

    protected function getPackageProviders($app)
    {
        return [
            ConsoleServiceProvider::class,
            ApprovalsServiceProvider::class
        ];
    }

    /**
     * Resolve application HTTP Kernel implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', Kernel::class);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

}
