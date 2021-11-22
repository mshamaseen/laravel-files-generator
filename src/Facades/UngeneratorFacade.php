<?php
namespace Shamaseen\Generator\Facades;

use Illuminate\Support\Facades\Facade;
use Shamaseen\Generator\Ungenerator;

class UngeneratorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    { return Ungenerator::class; }
}
