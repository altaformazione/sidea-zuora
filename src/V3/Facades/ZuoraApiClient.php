<?php

namespace Sideagroup\Zuora\V3\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sideagroup\Zuora\V3\Client\Client
 *
 * @mixin \Illuminate\Support\Facades\Http
 */
class ZuoraApiClient extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'zuora-api-client-v3';
    }
}
