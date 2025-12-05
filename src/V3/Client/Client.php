<?php

namespace Sideagroup\Zuora\V3\Client;

use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Traits\ForwardsCalls;
use Ramsey\Uuid\Uuid;
use Sideagroup\Zuora\V3\Client\Token\Manager;
use Str;

/**
 * @mixin \Illuminate\Http\Client\PendingRequest
 */
class Client
{
    protected const ZUORA_ENTITY_IDS_HEADER_KEY = 'zuora-entity-ids';

    protected const ZUORA_TRACK_ID_HEADER_KEY = 'zuora-track-id';

    use ForwardsCalls;

    protected Manager $token_manager;

    /**
     * @var array<string,string>
     */
    protected array $default_headers = [];

    public function __construct(
        protected ClientConfiguration $config
    ) {
        $this->token_manager = new Manager($this->config);
        $this->addZuoraEntityIdHeader();
    }

    protected function addZuoraEntityIdHeader(): void
    {
        if (! empty($this->config->entity_id)) {
            $this->addDefaultHeader(static::ZUORA_ENTITY_IDS_HEADER_KEY, $this->config->entity_id);
        }
    }

    public function getConfiguration(): ClientConfiguration
    {
        return $this->config;
    }

    protected function addDefaultHeader(string $key, string $value): self
    {
        $this->default_headers[$key] = $value;

        return $this;
    }

    /**
     * @return array<string,string>
     */
    protected function getDefaultHeaders(): array
    {
        return $this->default_headers;
    }

    protected function addZuoraTrackIdHeader(): void
    {
        $this->addDefaultHeader(static::ZUORA_TRACK_ID_HEADER_KEY, Str::uuid());
    }

    protected function buildBaseRequest(): PendingRequest
    {
        $this->addZuoraTrackIdHeader();

        return Http::baseUrl($this->config->base_uri)
            ->withHeaders($this->getDefaultHeaders())
            ->withToken($this->token_manager->getToken());
        // ->
        // ->withRequestMiddleware(function (Psr7Request $request) {
        //     $authorization = new ClientAuthorization($this->config);

        //     $authorization->setRequest(new Request($request));

        //     return $request
        //         ->withHeader('authorization', (string) $authorization)
        //         ->withHeader('x-netsuite-idempotency-key', (string) Uuid::uuid4());
        // })
        // ->when($this->config->logging, function (PendingRequest &$pending_request) {
        //     $pending_request->withRequestMiddleware(function (Psr7Request $request) {
        //         $method = $request->getMethod();
        //         $uri = $request->getUri();
        //         $ns_idempotency_key = $request->getHeader('x-netsuite-idempotency-key')[0];
        //         $authorization = $request->getHeader('authorization')[0];

        //         Log::info('Netsuite client REST API request', [
        //             'method' => (string) $method,
        //             'uri' => (string) $uri,
        //             'ns-idempotency-key' => (string) $ns_idempotency_key,
        //             'authorization' => (string) $authorization,
        //         ]);

        //         return $request;
        //     });
        // })

    }

    /**
     * @param  array<mixed>  $parameters
     */
    public function __call(
        string $method,
        array $parameters
    ): mixed {
        return $this->forwardCallTo(
            $this->buildBaseRequest(),
            $method,
            $parameters
        );
    }
}
