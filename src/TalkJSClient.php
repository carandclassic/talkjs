<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS;

use CarAndClassic\TalkJS\Api\ConversationApi;
use CarAndClassic\TalkJS\Api\MessageApi;
use CarAndClassic\TalkJS\Api\UserApi;
use CarAndClassic\TalkJS\TestApi\ConversationApiFake;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class TalkJSClient
{
    public UserApi $users;

    public ConversationApi $conversations;

    public MessageApi $messages;
    private HttpClientInterface $httpClient;

    public function __construct(string $appId, string $secretKey)
    {
        $this->httpClient = HttpClient::create([
            'base_uri' => 'https://api.talkjs.com/v1/' . $appId . '/',
            'auth_bearer' => $secretKey,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
        ]);
        $this->users = new UserApi($this->httpClient);
        $this->conversations = new ConversationApi($this->httpClient);
        $this->messages = new MessageApi($this->httpClient);
    }

    public function fake(bool $conversations = true, bool $messages = false, bool $users = false): void
    {
        if (!($conversations || $messages || $users)) {
            return;
        }

        if ($conversations) {
            $this->conversations = new ConversationApiFake($this->httpClient);
        }
//        if ($messages) {
//            $this->messages = new MessageApiFake($this->httpClient);
//        }
//        if ($users) {
//            $this->users = new UserApiFake($this->httpClient);
//        }
    }
}
