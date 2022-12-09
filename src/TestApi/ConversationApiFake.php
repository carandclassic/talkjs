<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\TestApi;

use CarAndClassic\TalkJS\Api\ConversationApi;
use PHPUnit\Framework\Assert as PHPUnit;

class ConversationApiFake extends ConversationApi
{
    private array $methodsCalled = [];

    public function get(array $filters = []): array
    {
        if (!array_key_exists('get', $this->methodsCalled)) {
            $this->methodsCalled['get'] = [];
        }
        $this->methodsCalled['get'][] = ['filters' => $filters];

        return [];
    }

    public function assertMethodCalled(string $method, $params): void
    {
        PHPUnit::assertArrayHasKey($method, $this->methodsCalled);
        PHPUnit::assertContains($params, $this->methodsCalled[$method]);
    }
}