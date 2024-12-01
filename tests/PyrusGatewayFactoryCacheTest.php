<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests;

use Psr\SimpleCache\CacheInterface;
use SuareSu\PyrusClient\Client\PyrusClientOptions;
use SuareSu\PyrusClient\Client\PyrusCredentials;
use SuareSu\PyrusClient\Gateway\PyrusGateway;
use SuareSu\PyrusClientSymfony\PyrusGatewayFactory;
use SuareSu\PyrusClientSymfony\PyrusGatewayFactoryCache;

/**
 * @internal
 */
final class PyrusGatewayFactoryCacheTest extends BaseCase
{
    /**
     * @test
     */
    public function testCreateGateway(): void
    {
        $login = 'login';
        $password = 'password';
        $personId = 'person_id';
        $credentialsHash = md5("{$login}_{$password}_{$personId}");
        $credentials = new PyrusCredentials($login, $password, $personId);

        $options = new PyrusClientOptions();

        $gateway = $this->mock(PyrusGateway::class);

        $innerFactory = $this->mock(PyrusGatewayFactory::class);
        $innerFactory->expects($this->once())
            ->method('createGateway')
            ->with(
                $this->identicalTo($credentials),
                $this->identicalTo($options)
            )
            ->willReturn($gateway);

        $cache = $this->mock(CacheInterface::class);
        $cache->expects($this->once())
            ->method('has')
            ->with(
                $this->identicalTo($credentialsHash)
            )
            ->willReturn(false);
        $cache->expects($this->once())
            ->method('set')
            ->with(
                $this->identicalTo($credentialsHash),
                $this->identicalTo($gateway)
            );

        $factory = new PyrusGatewayFactoryCache($innerFactory, $cache);
        $res = $factory->createGateway($credentials, $options);

        $this->assertSame($gateway, $res);
    }

    /**
     * @test
     */
    public function testCreateGatewayCached(): void
    {
        $login = 'login';
        $password = 'password';
        $personId = 'person_id';
        $credentialsHash = md5("{$login}_{$password}_{$personId}");
        $credentials = new PyrusCredentials($login, $password, $personId);

        $options = new PyrusClientOptions();

        $gateway = $this->mock(PyrusGateway::class);

        $innerFactory = $this->mock(PyrusGatewayFactory::class);
        $innerFactory->expects($this->never())->method('createGateway');

        $cache = $this->mock(CacheInterface::class);
        $cache->expects($this->once())
            ->method('has')
            ->with(
                $this->identicalTo($credentialsHash)
            )
            ->willReturn(true);
        $cache->expects($this->once())
            ->method('get')
            ->with(
                $this->identicalTo($credentialsHash)
            )
            ->willReturn($gateway);

        $factory = new PyrusGatewayFactoryCache($innerFactory, $cache);
        $res = $factory->createGateway($credentials, $options);

        $this->assertSame($gateway, $res);
    }
}
