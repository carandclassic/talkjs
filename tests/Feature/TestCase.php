<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Tests\Feature;

use CarAndClassic\TalkJS\Api\TalkJSApi;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

abstract class TestCase extends BaseTestCase
{
    protected array $defaultMockResponseHeaders;

    protected array $filters;

    protected function setUp(): void
    {
        $this->defaultMockResponseHeaders = [
            'Content-Type' => 'application/json'
        ];
        $this->filters = [
            'limit' => 10,
            'startingAfter' => 1
        ];
    }

    public function createApiWithMockHttpClient(array $mockResponses, string $apiClass): TalkJSApi
    {
        if (!is_subclass_of($apiClass, TalkJSApi::class)) {
            throw new \InvalidArgumentException('$apiClass is not an instance of TalkJSApi');
        }

        foreach ($mockResponses as $index => $mockResponse) {
            if (!$mockResponse instanceof MockResponse) {
                throw new \InvalidArgumentException(
                    '$mockResponses index '
                    . $index
                    . ' is not an instance of '
                    . MockResponse::class
                );
            }
        }

        $mockHttpClient = new MockHttpClient($mockResponses, 'https://api.talkjs.com/v1/testAppId/');

        return new $apiClass($mockHttpClient);
    }
}
