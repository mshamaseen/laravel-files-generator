<?php

namespace Shamaseen\Generator;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Shamaseen\Generator\Commands\GenerateFromConfig;
use Shamaseen\Generator\Commands\GenerateFromStub;
use Shamaseen\Generator\Facades\GeneratorFacade;

class GeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('FilesGenerator', GeneratorFacade::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/generator.php' => config_path('generator.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateFromStub::class,
                GenerateFromConfig::class,
            ]);
        }
    }
}
