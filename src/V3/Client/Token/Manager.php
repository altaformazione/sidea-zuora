<?php

namespace Sideagroup\Zuora\V3\Client\Token;

use Sideagroup\Zuora\V3\Client\ClientConfiguration;

final class Manager
{
    protected const CACHE_KEY_PREFIX = Token::class;

    public function __construct(
        protected ClientConfiguration $config
    ) {
        $this->validateEntityId();
    }

    protected function validateEntityId(): void
    {
        if (! empty($this->config->entity_id)) {
            if (! $this->getToken()->isZuoraEntityIdValidForToken($this->config->entity_id)) {
                throw new \Exception('Zuora entity ID "'.$this->config->entity_id.'" is not valid for token');
            }
        }
    }

    protected function getCacheKey(): string
    {
        return $this->config->getCacheId()
            ->start('\\')
            ->start(self::CACHE_KEY_PREFIX)
            ->toString();
    }

    protected function getCachedToken(): ?Token
    {
        /**
         * @var ?Token
         */
        $instance = cache()->get($this->getCacheKey());

        if (! ($instance instanceof Token && $instance->valid())) {
            return null;
        }

        return $instance;
    }

    protected function newCachedToken(): Token
    {
        $instance = new Token($this->config);

        cache()->set(
            $this->getCacheKey(),
            $instance,
            $instance->valid_until
        );

        return $instance;
    }

    public function getToken(): Token
    {
        return $this->getCachedToken() ?: $this->newCachedToken();
    }
}
