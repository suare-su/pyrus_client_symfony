<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests;

use SuareSu\PyrusClient\Client\PyrusClientOptions;
use SuareSu\PyrusClient\Client\PyrusCredentials;
use SuareSu\PyrusClient\DataConverter\PyrusDataConverter;
use SuareSu\PyrusClient\Gateway\PyrusGateway;
use SuareSu\PyrusClient\Transport\PyrusTransport;
use SuareSu\PyrusClientSymfony\PyrusGatewayFactoryImpl;

/**
 * @internal
 */
final class PyrusGatewayFactoryImplTest extends BaseCase
{
    /**
     * @test
     */
    public function testCreateGateway(): void
    {
        $transport = $this->mock(PyrusTransport::class);
        $dataConverter = $this->mock(PyrusDataConverter::class);
        $defaultOptions = new PyrusClientOptions();

        $credentials = new PyrusCredentials('test', 'test');

        $factory = new PyrusGatewayFactoryImpl($transport, $dataConverter, $defaultOptions);
        $res = $factory->createGateway($credentials);

        $this->assertInstanceOf(PyrusGateway::class, $res);
        $this->assertSame($defaultOptions, $res->getClient()->getOptions());
        $this->assertTrue($res->getClient()->hasCredentials());
    }
}
