<?php

namespace Code16\ContentRenderer\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\View\DynamicComponent;
use Orchestra\Testbench\TestCase as Orchestra;
use Code16\ContentRenderer\ContentRendererServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // DynamicComponent statically caches component aliases and classes across tests
        (fn () => [static::$compiler = null, static::$componentClasses = []])
            ->bindTo(null, DynamicComponent::class)();
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
