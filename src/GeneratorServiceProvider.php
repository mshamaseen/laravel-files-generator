<?php

namespace Shamaseen\Generator;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Shamaseen\Generator\Commands\GenerateFromConfig;
use Shamaseen\Generator\Commands\GenerateFromStub;
use Shamaseen\Generator\Commands\UngenerateFromConfig;
use Shamaseen\Generator\Facades\GeneratorFacade;
use Shamaseen\Generator\Facades\UngeneratorFacade;

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
        $loader->alias('FilesUngenerator', UngeneratorFacade::class);
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
                UngenerateFromConfig::class,
            ]);
        }
    }
}
