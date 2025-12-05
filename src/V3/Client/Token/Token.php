<?php

namespace Sideagroup\Zuora\V3\Client\Token;

use Carbon\CarbonImmutable;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Sideagroup\Zuora\V3\Client\ClientConfiguration;

final class Token implements \Stringable
{
    public const GRANT_TYPE = 'client_credentials';

    public const AUTH_ENDPOINT_PATH = '/oauth/token';

    protected ResponseBody $response_body;

    public CarbonImmutable $valid_until;

    public function __construct(
        protected ClientConfiguration $config
    ) {
        $this->retriveToken();
    }

    /**
     * @return \Illuminate\Support\Collection<int,string>
     */
    protected function getZuoraEntities(): Collection
    {
        return $this
            ->response_body
            ->zuora_entities
            ->map(function ($value) {
                return str($value)->remove('-')->toString();
            });
    }

    public function isZuoraEntityIdValidForToken(string $neededZuoraEntityId): bool
    {
        return $this
            ->getZuoraEntities()
            ->contains($neededZuoraEntityId);
    }

    protected function getAuthenticationEndpoint(): string
    {
        return str($this->config->base_uri)->finish(self::AUTH_ENDPOINT_PATH)->toString();
    }

    protected function parseResponse(Response $rawResponse): static
    {
        $this->response_body = new ResponseBody(
            $rawResponse->throw()
        );

        $this->valid_until = CarbonImmutable::now()->addSeconds((int) $this->response_body->expires_in);

        return $this;
    }

    public function valid(): bool
    {
        return ! empty($this->valid_until)
            && $this->valid_until->isAfter(now());
    }

    public function retriveToken(): static
    {
        $raw_response = Http::asForm()
            ->withHeaders([
                'Accept' => 'application/json; charset=utf-8',
            ])
            ->post(
                $this->getAuthenticationEndpoint(),
                [
                    'client_id' => $this->config->client_id,
                    'client_secret' => $this->config->client_secret,
                    'grant_type' => self::GRANT_TYPE,
                ]
            );

        return $this->parseResponse($raw_response);
    }

    public function getAccessToken(): string
    {
        return $this->response_body->access_token;
    }

    public function __toString(): string
    {
        return $this->getAccessToken();
    }
}
