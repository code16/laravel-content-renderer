<?php

namespace Code16\LaravelContentRenderer\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Code16\LaravelContentRenderer\ContentRendererServiceProvider;

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
