<?php

namespace Code16\ContentRenderer\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Code16\ContentRenderer\ContentRendererServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            ContentRendererServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
    }
}
