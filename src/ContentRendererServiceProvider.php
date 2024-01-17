<?php

namespace Code16\ContentRenderer;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ContentRendererServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'content');
        Blade::componentNamespace('Code16\\ContentRenderer\\View\\Components', 'content');
    }
}
