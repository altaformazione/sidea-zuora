<?php

namespace Sideagroup\Zuora\V3\Client\Token;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

class ResponseBody
{
    public const ZUORA_ENTITY_SCOPE_REGEX = '/^entity\.(.*)/';

    public string $access_token;

    public string $expires_in;

    public string $jti;

    public string $token_type;

    /**
     * @var Collection<int,string>
     */
    public Collection $scopes;

    /**
     * @var Collection<int,string>
     */
    public Collection $zuora_entities;

    public function __construct(Response $rawResponse)
    {
        [
            'access_token' => $this->access_token,
            'token_type' => $this->token_type,
            'expires_in' => $this->expires_in,
            'scope' => $scopeString,
            'jti' => $this->jti,
        ] = $rawResponse;

        $this
            ->setScopes($scopeString)
            ->setZuoraEntities();
    }

    protected function setScopes(string $scopeString): static
    {
        $this->scopes = collect(explode(' ', $scopeString));

        return $this;
    }

    protected function setZuoraEntities(): static
    {
        $this->zuora_entities = $this->scopes
            ->reduce(function (Collection $zuora_entities, string $scope) {
                $count = preg_match(
                    self::ZUORA_ENTITY_SCOPE_REGEX,
                    $scope,
                    $matches
                );

                if ($count > 0) {
                    $zuora_entities->push($matches[1]);
                }

                return $zuora_entities;
            }, collect([]));

        return $this;
    }
}
