<?php

class TestCase extends Orchestra\Testbench\TestCase
{

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Your code here
    }

    protected function getPackageProviders($app)
    {
        return ['Acme\AcmeServiceProvider'];
    }
}
