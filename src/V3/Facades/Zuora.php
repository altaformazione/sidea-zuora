<?php

namespace Sideagroup\Zuora\V3\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sideagroup\Zuora\V3\Services\Zuora
 *
 * @mixin \Sideagroup\Zuora\V3\Services\Zuora;
 */
class Zuora extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sideagroup\Zuora\V3\Services\Zuora::class;
    }
}
