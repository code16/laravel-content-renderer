<?php

namespace Code16\LaravelContentRenderer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Code16\LaravelContentRenderer\LaravelContentRenderer
 */
class LaravelContentRenderer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Code16\LaravelContentRenderer\LaravelContentRenderer::class;
    }
}
