<?php

namespace Sideagroup\Zuora\V3\Client;

use Illuminate\Support\Arr;
use Illuminate\Support\Stringable;

final readonly class ClientConfiguration
{
    public function __construct(
        public string $base_uri,
        public string $client_id,
        public string $client_secret,
        public string $entity_id,
        public bool $log_requests,
    ) {}

    /**
     * @param  array<string>  $config
     */
    public static function fromConfig(
        array $config
    ): static {
        return new self(
            base_uri: Arr::get($config, 'base_uri'),
            client_id: Arr::get($config, 'credentials.client_id'),
            client_secret: Arr::get($config, 'credentials.client_secret'),
            entity_id: Arr::get($config, 'credentials.entity_id'),
            log_requests: (bool) Arr::get($config, 'log_requests')
        );
    }

    public function getCacheId(): Stringable
    {
        return str(md5(collect([$this->base_uri, $this->client_id, $this->client_secret])->join(':')));
    }
}
