<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony;

use SuareSu\PyrusClient\Client\PyrusClientOptions;
use SuareSu\PyrusClient\Exception\PyrusClientException;
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
        $symfonyOptions = $this->prepareBaseSymfonyOptions($request, $options);

        if (null !== $request->payload && !empty($request->payload)) {
            if (PyrusRequestMethod::GET === $request->method) {
                $symfonyOptions['query'] = $request->payload;
            } else {
                $symfonyOptions['json'] = $request->payload;
            }
        }

        return $this->runSymfonyRequest($request, $symfonyOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function uploadFile(PyrusRequest $request, \SplFileInfo $file, ?PyrusClientOptions $options = null): PyrusResponse
    {
        if (!$file->isFile()) {
            throw new PyrusClientException("Can't open '{$file->getRealPath()}' file");
        }

        $symfonyOptions = $this->prepareBaseSymfonyOptions($request, $options);
        $fh = fopen($file->getRealPath(), 'r');
        $symfonyOptions['body'] = [
            'file' => $fh,
        ];

        $response = $this->runSymfonyRequest($request, $symfonyOptions);

        fclose($fh);

        return $response;
    }

    /**
     * Extract array with base options for Symfony HTTP client.
     */
    private function prepareBaseSymfonyOptions(PyrusRequest $request, ?PyrusClientOptions $options = null): array
    {
        $symfonyOptions = [];

        if (!empty($request->headers)) {
            $symfonyOptions['headers'] = $request->headers;
        }

        if (null !== $options) {
            $symfonyOptions['max_duration'] = $options->timeout;
        }

        return $symfonyOptions;
    }

    /**
     * Run request using Symfony HTTP client.
     */
    private function runSymfonyRequest(PyrusRequest $request, array $options = []): PyrusResponse
    {
        $response = $this->symfonyTransport->request(
            $request->method->value,
            $request->url,
            $options
        );

        return new PyrusResponse(
            PyrusResponseStatus::from($response->getStatusCode()),
            $response->getContent()
        );
    }
}
