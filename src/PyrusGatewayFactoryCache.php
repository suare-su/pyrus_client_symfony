<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony;

use Psr\SimpleCache\CacheInterface;
use SuareSu\PyrusClient\Client\PyrusClientOptions;
use SuareSu\PyrusClient\Client\PyrusCredentials;
use SuareSu\PyrusClient\Gateway\PyrusGateway;

/**
 * Wrapper for factory that caches created objects.
 *
 * @internal
 *
 * @psalm-api
 */
final class PyrusGatewayFactoryCache implements PyrusGatewayFactory
{
    public function __construct(
        private readonly PyrusGatewayFactory $innerFactory,
        private readonly CacheInterface $cache
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function createGateway(PyrusCredentials $credentials, ?PyrusClientOptions $options = null): PyrusGateway
    {
        $key = $this->getCredentialsHash($credentials);

        if ($this->cache->has($key)) {
            /** @var PyrusGateway */
            $gateway = $this->cache->get($key);
        } else {
            $gateway = $this->innerFactory->createGateway($credentials, $options);
            $this->cache->set($key, $gateway);
        }

        return $gateway;
    }

    private function getCredentialsHash(PyrusCredentials $credentials): string
    {
        return md5("{$credentials->login}_{$credentials->securityKey}_{$credentials->personId}");
    }
}
