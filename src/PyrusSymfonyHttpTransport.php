<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony;

use SuareSu\PyrusClient\Client\PyrusClientOptions;
use SuareSu\PyrusClient\Transport\PyrusRequest;
use SuareSu\PyrusClient\Transport\PyrusRequestMethod;
use SuareSu\PyrusClient\Transport\PyrusResponse;
use SuareSu\PyrusClient\Transport\PyrusResponseStatus;
use SuareSu\PyrusClient\Transport\PyrusTransport;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Facade for HTTP client.
 *
 * @psalm-api
 */
final class PyrusSymfonyHttpTransport implements PyrusTransport
{
    public function __construct(private HttpClientInterface $symfonyTransport)
    {
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress MixedArrayAssignment
     */
    public function request(PyrusRequest $request, ?PyrusClientOptions $options = null): PyrusResponse
    {
        $symfonyOptions = [];

        if (!empty($request->headers)) {
            $symfonyOptions['headers'] = $request->headers;
        }

        if (PyrusRequestMethod::GET === $request->method) {
            $symfonyOptions['query'] = $request->payload;
        } else {
            $symfonyOptions['json'] = $request->payload;
        }

        if (null !== $options) {
            $symfonyOptions['max_duration'] = $options->timeout;
        }

        $response = $this->symfonyTransport->request(
            $request->method->value,
            $request->url,
            $symfonyOptions
        );

        return new PyrusResponse(
            PyrusResponseStatus::from($response->getStatusCode()),
            $response->getContent()
        );
    }
}
