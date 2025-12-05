<?php

namespace Sideagroup\Zuora\V2\Oauth;

use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Token
{
    public const RESOURCE_PATH = '/oauth/token';

    public const GRANT_TYPE = 'client_credentials';

    protected const CACHE_KEY_PREFIX = '\\Sideagroup\\Zuora\\ApiClient\\Token\\';

    protected ResponseBody $response_body;

    public CarbonImmutable $valid_until;

    public function __construct(
        public string $base_uri,
        public string $client_id,
        public string $client_secret
    ) {}

    public function __toString(): string
    {
        return $this->getAccessToken();
    }

    public function getAccessToken(): string
    {
        return $this
            ->retriveToken()
            ->response_body
            ->accessToken;
    }

    public function getBaseUri()
    {
        return $this->base_uri;
    }

    public function isZuoraEntityIdValidForToken(string $neededZuoraEntityId): bool
    {
        return $this
            ->_getZuoraEntities()
            ->contains($neededZuoraEntityId);
    }

    public static function restoreOrNew(
        string $base_uri,
        string $client_id,
        string $client_secret
    ) {
        try {
            return self::_restoreInstance(
                $base_uri,
                $client_id,
                $client_secret
            );
        } catch (Exception $e) {
            return new self(
                $base_uri,
                $client_id,
                $client_secret
            );
        }
    }

    public function retriveToken(
        bool $force = false
    ): self {
        if (! $force && $this->_isValid()) {
            return $this;
        }

        $rawResponse = Http::asForm()
            ->withHeaders([
                'Accept' => 'application/json; charset=utf-8',
            ])
            ->post(
                $this->_getAuthenticationEndpoint(),
                [
                    'client_id' => $this->client_id,
                    'client_secret' => $this->client_secret,
                    'grant_type' => self::GRANT_TYPE,
                ]
            );

        $this->_parseResponse($rawResponse);

        $this->_cacheInstance();

        return $this;
    }

    protected function _cacheInstance()
    {
        $cacheKey = self::_getCacheKey(
            $this->base_uri,
            $this->client_id,
            $this->client_secret
        );

        Cache::put($cacheKey, serialize($this));

        return true;
    }

    protected function _getAuthenticationEndpoint(): string
    {
        return $this->getBaseUri().self::RESOURCE_PATH;
    }

    protected static function _getCacheKey(
        string $base_uri,
        string $client_id,
        string $client_secret
    ) {
        return self::CACHE_KEY_PREFIX.md5("$base_uri:$client_id:$client_secret");
    }

    protected function _getZuoraEntities(): Collection
    {
        return $this
            ->retriveToken()
            ->response_body
            ->zuoraEntities
            ->map(function ($value) {
                return str($value)->remove('-')->toString();
            });
    }

    protected function _isValid()
    {
        if (empty($this->valid_until)) {
            return false;
        }

        return $this->valid_until->isAfter(CarbonImmutable::now());
    }

    protected function _parseResponse(Response $rawResponse)
    {
        $this->response_body = new ResponseBody(
            $rawResponse->throw()
        );

        $this->valid_until = CarbonImmutable::now()->addSeconds((int) $this->response_body->expiresIn);
    }

    protected static function _restoreInstance(
        string $base_uri,
        string $client_id,
        string $client_secret
    ): self {
        $cacheKey = self::_getCacheKey(
            $base_uri,
            $client_id,
            $client_secret
        );

        if (! Cache::has($cacheKey)
            || ! ($instance = unserialize(Cache::get($cacheKey)))
            || ! ($instance instanceof self)) {
            throw new Exception('Token instance never cached or cached in a wrong way');
        }

        return $instance;
    }
}
