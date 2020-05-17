<?php

namespace Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

/**
 * Replaces TestCase.
 */
class TestCase extends OrchestraTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/../database/factories');
    }

    protected function getPackageProviders($app)
    {
        return [
            \Orchestra\Database\ConsoleServiceProvider::class,
        ];
    }

    /**
     * Defines environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections', [
            'sqlite' => [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ],
        ]);
    }

    /**
     * Loads the base migrations.
     *
     * @param  string $database
     * @return void
     */
    protected function loadBaseMigrations(string $database = 'sqlite')
    {
        $this->loadMigrationsFrom([
            '--realpath' => realpath(__DIR__.'/../database/migrations'),
            '--database' => $database,
        ]);
    }
}
