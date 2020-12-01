<?php
namespace GGPHP\FileMedia\Tests;

use GGPHP\FileMedia\Providers\FileMediaServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->artisan('migrate', ['--database' => 'dbtest']);

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');
        });
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'dbtest');
        $app['config']->set('database.connections.dbtest', [
            'driver'   => 'mysql',
            'database' => 'dbtest',
            'prefix'   => '',
            'host'   => 'dbtest',
            'username' => 'dbuser',
            'password' => 'user123',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [FileMediaServiceProvider::class];
    }
}
