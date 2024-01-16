<?php

namespace Code16\LaravelContentRenderer;

use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ContentRendererServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-content-renderer')
            ->hasConfigFile()
            ->hasViews('content');

        Blade::componentNamespace('Code16\\LaravelContentRenderer\\View\\Components', 'content');
    }
}
