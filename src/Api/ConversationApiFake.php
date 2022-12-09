<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Api;

use Symfony\Contracts\HttpClient\HttpClientInterface;

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
        if (!array_key_exists($method, $this->methodsCalled)) {
            throw new \Exception("Method $method was not called");
        }
        if (!in_array($params, $this->methodsCalled[$method])) {
            throw new \Exception("Method $method was not called with params " . json_encode($params));
        }
    }
}