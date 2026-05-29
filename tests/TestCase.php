<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    /**
     * Force the entire test suite onto an isolated database.
     *
     * The Docker container exports DB_DATABASE=dotportal as a real OS env var,
     * which Laravel's env() reads with priority over phpunit.xml <env> — so the
     * config must be overridden here, before RefreshDatabase migrates, to guarantee
     * tests never touch the dev database.
     *
     * Create the database once:  CREATE DATABASE dotportal_test;
     * Override the name if needed with a real DB_TEST_DATABASE env var.
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        // The container injects APP_ENV=local as a real env var, which overrides
        // phpunit.xml's APP_ENV=testing. Force it back so framework test behaviour
        // works (e.g. CSRF auto-skips via runningUnitTests() under Sanctum statefulApi).
        $app['env'] = 'testing';
        $app['config']->set('app.env', 'testing');

        $testDb = env('DB_TEST_DATABASE', 'dotportal_test');
        $app['config']->set('database.connections.pgsql.database', $testDb);
        DB::purge('pgsql');

        return $app;
    }
}
