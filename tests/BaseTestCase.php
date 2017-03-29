<?php

namespace Swatkins\Approvals\Tests;

use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\Http\Kernel;
use Swatkins\Approvals\Models\Widget;
use Swatkins\Approvals\Tests\Stubs\User;
use Orchestra\Database\ConsoleServiceProvider;
use Swatkins\Approvals\ApprovalsServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class BaseTestCase extends OrchestraTestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'testbench']);

        $this->artisan('migrate', ['--database' => 'testbench', '--step' => '']);

        $this->withFactories(__DIR__ . '/../database/factories');

    }

    public function tearDown()
    {
        $tables = DB::select('select * from sqlite_master where type="table"');

        foreach($tables as $table) {
            if ( ! str_contains($table->name, 'sqlite_') ) {
                DB::statement("DROP TABLE $table->name");
            }
        }
    }

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
            'database' => ':memory:', //__DIR__ . '/database.sqlite',
            'prefix'   => '',
        ]);
        $app['config']->set('approvals.requester_model', User::class);
        $app['config']->set('approvals.approver_model', User::class);
    }

    /**
     * @return array
     */
    protected function setupAndCreateApproval($requesterEmail = 'requester@example.com', $approverEmail = 'approver@example.com', $widgetName = 'Test Widget'): array
    {
        $requester = factory(config('approvals.requester_model'))->create(['email' => $requesterEmail]);
        $approver = factory(config('approvals.approver_model'))->create(['email' => $approverEmail]);
        $widget = factory(Widget::class)->create(['name' => $widgetName, 'owner_id' => $requester->id]);
        $approval = $widget->requestApprovalFrom($approver);
        $widget->load('approval');
        return array($requester, $approver, $widget, $approval);
    }

}
