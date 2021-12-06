<?php

namespace Shamaseen\Generator\Tests;

use Shamaseen\Generator\GeneratorServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        config()->set('generator.base_path', '');
    }

    protected function getPackageProviders($app): array
    {
        return [
            GeneratorServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
