<?php

namespace Sideagroup\Zuora\V2\Oauth;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

class ResponseBody
{
    public const ZUORA_ENTITY_SCOPE_REGEX = '/^entity\.(.*)/';

    public string $accessToken;

    public string $expiresIn;

    public string $jti;

    public Collection $scopes;

    public string $tokenType;

    public Collection $zuoraEntities;

    public function __construct(Response $raw_response)
    {
        [
            'access_token' => $this->accessToken,
            'token_type' => $this->tokenType,
            'expires_in' => $this->expiresIn,
            'scope' => $scopeString,
            'jti' => $this->jti,
        ] = $raw_response;

        $this
            ->setScopes($scopeString)
            ->setZuoraEntities();
    }

    protected function setScopes(string $scopeString)
    {
        $this->scopes = collect(explode(' ', $scopeString));

        return $this;
    }

    protected function setZuoraEntities()
    {
        $this->zuoraEntities = $this->scopes
            ->reduce(function (Collection $zuoraEntities, string $scope) {
                $count = preg_match(
                    self::ZUORA_ENTITY_SCOPE_REGEX,
                    $scope,
                    $matches
                );

                if ($count > 0) {
                    $zuoraEntities->push($matches[1]);
                }

                return $zuoraEntities;
            }, collect([]));

        return $this;
    }
}
