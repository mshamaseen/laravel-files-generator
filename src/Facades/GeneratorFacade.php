<?php
namespace Shamaseen\Generator\Facades;

use Illuminate\Support\Facades\Facade;
use Shamaseen\Generator\Generator;

class GeneratorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return Generator::class; }
}
