<?php

namespace Sideagroup\Zuora\V3\Services;

use Sideagroup\Zuora\V3\Client\Client;

abstract class AbstractService
{
    public function __construct(
        protected Client $client
    ) {}
}
