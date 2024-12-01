<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony;

use SuareSu\PyrusClient\Client\PyrusClientImpl;
use SuareSu\PyrusClient\Client\PyrusClientOptions;
use SuareSu\PyrusClient\Client\PyrusCredentials;
use SuareSu\PyrusClient\DataConverter\PyrusDataConverter;
use SuareSu\PyrusClient\Gateway\PyrusGateway;
use SuareSu\PyrusClient\Gateway\PyrusGatewayImpl;
use SuareSu\PyrusClient\Transport\PyrusTransport;

/**
 * Constructs gateway objects for different clients.
 *
 * @internal
 *
 * @psalm-api
 */
final class PyrusGatewayFactoryImpl implements PyrusGatewayFactory
{
    public function __construct(
        private readonly PyrusTransport $transport,
        private readonly PyrusDataConverter $dataConverter,
        private readonly PyrusClientOptions $defaultOptions,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function createGateway(PyrusCredentials $credentials, ?PyrusClientOptions $options = null): PyrusGateway
    {
        $options = $options ?? $this->defaultOptions;

        $client = new PyrusClientImpl($this->transport, $options);
        $client->useAuthCredentials($credentials);

        return new PyrusGatewayImpl($client, $this->dataConverter);
    }
}
