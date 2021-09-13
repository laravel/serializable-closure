# Serializable Closure

<a href="https://github.com/laravel/serializable-closure/actions">
    <img src="https://github.com/laravel/serializable-closure/workflows/tests/badge.svg" alt="Build Status">
</a>
<a href="https://packagist.org/packages/laravel/serializable-closure">
    <img src="https://img.shields.io/packagist/dt/laravel/serializable-closure" alt="Total Downloads">
</a>
<a href="https://packagist.org/packages/laravel/serializable-closure">
    <img src="https://img.shields.io/packagist/v/laravel/serializable-closure" alt="Latest Stable Version">
</a>
<a href="https://packagist.org/packages/laravel/serializable-closure">
    <img src="https://img.shields.io/packagist/l/laravel/serializable-closure" alt="License">
</a>

## Introduction

> This package is a work in progress

Laravel Serializable Closure provides an easy way to serialize closures in PHP. It's a fork of [opis/closure: ^3.0](https://github.com/opis/closure) that **does not use the PHP FFI Extension**, and supports PHP 8.1.

## Installation / Usage

> **Requires [PHP 7.3+](https://php.net/releases/)**

First, install Laravel Serializable Closure via the [Composer](https://getcomposer.org/) package manager:

```bash
composer require laravel/serializable-closure --dev
```

Then, you may serialize a closure this way:

```php
use Laravel\SerializableClosure\SerializableClosure;

$closure = fn () => 'james';

SerializableClosure::setSecretKey('secret');

$serialized = serialize(new SerializableClosure($closure));
$closure = unserialize($serialized)->getClosure();

echo $closure(); // james;
```

## Limitations

- Creating **anonymous classes** within closures is not supported.

## License

Seriazable Closure is open-sourced software licensed under the [MIT license](LICENSE.md).
