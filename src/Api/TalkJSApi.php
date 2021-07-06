<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Api;

use CarAndClassic\TalkJS\Exceptions\Api\BadRequestException;
use CarAndClassic\TalkJS\Exceptions\Api\NotFoundException;
use CarAndClassic\TalkJS\Exceptions\Api\TooManyRequestsException;
use CarAndClassic\TalkJS\Exceptions\Api\UnauthorizedException;
use CarAndClassic\TalkJS\Exceptions\Api\UnknownErrorException;
use CarAndClassic\TalkJS\Exceptions\LogicException;
use CarAndClassic\TalkJS\Exceptions\ResponseException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class TalkJSApi
{
    protected HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Send a GET request with query parameters.
     * @throws TransportExceptionInterface
     */
    protected function httpGet(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpRequest('GET', $path, $params, $requestHeaders);
    }

    /**
     * Send a POST request with JSON-encoded parameters.
     * @throws TransportExceptionInterface
     */
    protected function httpPost(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpRequest('POST', $path, $params, $requestHeaders);
    }

    /**
     * Send a PUT request with JSON-encoded parameters.
     * @throws TransportExceptionInterface
     */
    protected function httpPut(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpRequest('PUT', $path, $params, $requestHeaders);
    }

    /**
     * Send a PATCH request with JSON-encoded parameters.
     * @throws TransportExceptionInterface
     */
    protected function httpPatch(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpRequest('PATCH', $path, $params, $requestHeaders);
    }

    /**
     * Send a DELETE request with JSON-encoded parameters.
     * @throws TransportExceptionInterface
     */
    protected function httpDelete(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpRequest('DELETE', $path, $params, $requestHeaders);
    }

    /**
     * Send an HTTP request with JSON-encoded parameters.
     * @param string $type
     * @param string $path
     * @param $params
     * @param array $requestHeaders
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    protected function httpRequest(string $type, string $path, $params = [], array $requestHeaders = []): ResponseInterface
    {
        $dataKey = 'query';
        if (in_array($type, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $dataKey = 'body';
            $params = $this->createJsonBody($params);
        }
        return $this->httpClient->request($type, $path, [
            $dataKey => $params,
            'headers' => $requestHeaders,
        ]);

    }

    /**
     * Create a JSON encoded version of an array of parameters.
     *
     * @throws LogicException
     */
    private function createJsonBody(array $params): ?string
    {
        if (0 === \count($params)) {
            return null;
        }

        $body = json_encode($params);

        if (!\is_string($body)) {
            throw new LogicException('An error occurred when encoding body: '.json_last_error_msg());
        }

        return $body;
    }

    /**
     * @param ResponseInterface $response
     * @return array
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     * @throws UnknownErrorException
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function parseResponseData(ResponseInterface $response): array
    {
        if ($response->getStatusCode() !== 200) {
            switch ($response->getStatusCode()) {
                case 400:
                    throw new BadRequestException($response);
                case 401:
                    throw new UnauthorizedException();
                case 404:
                    throw new NotFoundException();
                case 429:
                    throw new TooManyRequestsException();
                default:
                    throw new UnknownErrorException($response);
            }
        }

        if (!isset($response->getHeaders()['content-type'])) {
            throw new ResponseException('No content-type header returned');
        }

        $contentType = reset($response->getHeaders()['content-type']);
        if (0 !== strpos($contentType, 'application/json')) {
            throw new ResponseException("Invalid content-type header. Expecting application/json, got $contentType");
        }

        $data = json_decode($response->getContent(), true);
        if (\JSON_ERROR_NONE !== json_last_error()) {
            throw new ResponseException(sprintf('Error (%d) when trying to json_decode response', json_last_error()));
        }
        return $data;
    }
}
