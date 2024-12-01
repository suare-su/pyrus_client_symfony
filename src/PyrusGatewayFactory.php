<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony;

use SuareSu\PyrusClient\Client\PyrusClientOptions;
use SuareSu\PyrusClient\Client\PyrusCredentials;
use SuareSu\PyrusClient\Gateway\PyrusGateway;

/**
 * Factory that can build Pyrus gateways for different clients.
 */
interface PyrusGatewayFactory
{
    /**
     * Create gateway object.
     */
    public function createGateway(PyrusCredentials $credentials, ?PyrusClientOptions $options = null): PyrusGateway;
}
