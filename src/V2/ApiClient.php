<?php

namespace Sideagroup\Zuora\V2;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Traits\ForwardsCalls;
use Sideagroup\Zuora\V2\Oauth\Token;
use Str;

/**
 * ApiClient class
 *
 * @method \Illuminate\Http\Client\PendingRequest accept(string $contentType)
 * @method \Illuminate\Http\Client\PendingRequest acceptJson()
 * @method \Illuminate\Http\Client\PendingRequest asForm()
 * @method \Illuminate\Http\Client\PendingRequest asJson()
 * @method \Illuminate\Http\Client\PendingRequest asMultipart()
 * @method \Illuminate\Http\Client\PendingRequest async()
 * @method \Illuminate\Http\Client\PendingRequest attach(string|array $name, string|resource $contents = '', string|null $filename = null, array $headers = [])
 * @method \Illuminate\Http\Client\PendingRequest baseUrl(string $url)
 * @method \Illuminate\Http\Client\PendingRequest beforeSending(callable $callback)
 * @method \Illuminate\Http\Client\PendingRequest bodyFormat(string $format)
 * @method \Illuminate\Http\Client\PendingRequest connectTimeout(int $seconds)
 * @method \Illuminate\Http\Client\PendingRequest contentType(string $contentType)
 * @method \Illuminate\Http\Client\PendingRequest dd()
 * @method \Illuminate\Http\Client\PendingRequest dump()
 * @method \Illuminate\Http\Client\PendingRequest retry(int $times, int $sleep = 0, ?callable $when = null, bool $throw = true)
 * @method \Illuminate\Http\Client\PendingRequest sink(string|resource $to)
 * @method \Illuminate\Http\Client\PendingRequest stub(callable $callback)
 * @method \Illuminate\Http\Client\PendingRequest timeout(int $seconds)
 * @method \Illuminate\Http\Client\PendingRequest withBasicAuth(string $username, string $password)
 * @method \Illuminate\Http\Client\PendingRequest withBody(resource|string $content, string $contentType)
 * @method \Illuminate\Http\Client\PendingRequest withCookies(array $cookies, string $domain)
 * @method \Illuminate\Http\Client\PendingRequest withDigestAuth(string $username, string $password)
 * @method \Illuminate\Http\Client\PendingRequest withHeaders(array $headers)
 * @method \Illuminate\Http\Client\PendingRequest withMiddleware(callable $middleware)
 * @method \Illuminate\Http\Client\PendingRequest withOptions(array $options)
 * @method \Illuminate\Http\Client\PendingRequest withToken(string $token, string $type = 'Bearer')
 * @method \Illuminate\Http\Client\PendingRequest withUserAgent(string $userAgent)
 * @method \Illuminate\Http\Client\PendingRequest withoutRedirecting()
 * @method \Illuminate\Http\Client\PendingRequest withoutVerifying()
 * @method array pool(callable $callback)
 * @method \Illuminate\Http\Client\Response delete(string $url, array $data = [])
 * @method \Illuminate\Http\Client\Response get(string $url, array|string|null $query = null)
 * @method \Illuminate\Http\Client\Response head(string $url, array|string|null $query = null)
 * @method \Illuminate\Http\Client\Response patch(string $url, array $data = [])
 * @method \Illuminate\Http\Client\Response post(string $url, array $data = [])
 * @method \Illuminate\Http\Client\Response put(string $url, array $data = [])
 * @method \Illuminate\Http\Client\Response send(string $method, string $url, array $options = [])
 */
class ApiClient
{
    use ForwardsCalls;

    protected Token $oauth_token;

    protected PendingRequest $request;

    protected array $default_headers = [];

    public function __construct(
        protected string $base_uri,
        protected string $client_id,
        protected string $client_secret,
        protected ?string $zuora_entity_id = null,
        protected bool $log_requests = false
    ) {
        $this->setOauthToken();

        if (! empty($zuora_entity_id)) {
            if (! $this->oauth_token->isZuoraEntityIdValidForToken($this->zuora_entity_id)) {
                throw new \Exception("Zuora entity ID \"$zuora_entity_id\" is not valid for token");
            }
            $this->addDefaultHeader('zuora-entity-ids', $this->zuora_entity_id);
        }
    }

    protected function addDefaultHeader(string $key, string $value): self
    {
        $this->default_headers[$key] = $value;

        return $this;
    }

    protected function getDefaultHeaders(): array
    {
        return $this->default_headers;
    }

    protected function addZuoraTrackIdHeader(): void
    {
        $this->addDefaultHeader('zuora-track-id', Str::uuid());
    }

    protected function buildRequest(): PendingRequest
    {
        $this->addZuoraTrackIdHeader();

        return Http::logWhen($this->log_requests) // @phpstan-ignore-line
            ->baseUrl($this->base_uri)
            ->withHeaders($this->getDefaultHeaders())
            ->withToken($this->oauth_token);
    }

    protected function setOauthToken(): self
    {
        $this->oauth_token = Token::restoreOrNew(
            $this->base_uri,
            $this->client_id,
            $this->client_secret,
        );

        return $this;
    }

    public function getOauthToken(): Token
    {
        return $this->oauth_token;
    }

    public function __call($name, $arguments)
    {
        return $this->forwardCallTo(
            $this->buildRequest(),
            $name,
            $arguments
        );
    }
}
