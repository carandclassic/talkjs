<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJS\Tests\Unit;

use CarAndClassic\TalkJS\Api\TalkJSApi;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

abstract class TestCase extends BaseTestCase
{
    protected array $defaultMockResponseHeaders = [
        'Content-Type' => 'application/json'
    ];

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
