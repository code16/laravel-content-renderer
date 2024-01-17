# Render blade components inside HTML content

[![Latest Version on Packagist](https://img.shields.io/packagist/v/code16/laravel-content-renderer.svg?style=flat-square)](https://packagist.org/packages/code16/laravel-content-renderer)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/code16/laravel-content-renderer/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/code16/laravel-content-renderer/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/code16/laravel-content-renderer.svg?style=flat-square)](https://packagist.org/packages/code16/laravel-content-renderer)

This package is used internally for our projects (e.g. [Sharp](https://github.com/code16/sharp)). It allows to render blade `<x-` components inside HTML content. 
For security reasons the content is not directly compiled in blade, instead it replace components with `<x-dynamic-component>` tag and pass HTML attributes.
All attributes passed to component are strings. Attributes starting with `:` are not evaluated.

## Installation

You can install the package via composer:

```bash
composer require code16/laravel-content-renderer
```

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
