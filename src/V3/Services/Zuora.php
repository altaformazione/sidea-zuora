<?php

namespace Sideagroup\Zuora\V3\Services;

use Sideagroup\Zuora\V3\Client\Client;
use Sideagroup\Zuora\V3\Services\Accounts\AccountsService;
use Sideagroup\Zuora\V3\Services\Catalog\CatalogService;

final class Zuora
{
    public function __construct(
        protected Client $client
    ) {}

    public function getClient(): Client
    {
        return $this->client;
    }

    public function catalog(): CatalogService
    {
        return new CatalogService($this->client);
    }

    public function accounts(): AccountsService
    {
        return new AccountsService($this->client);
    }
}
