<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS;

use CarAndClassic\TalkJS\Api\ConversationApi;
use CarAndClassic\TalkJS\Api\MessageApi;
use CarAndClassic\TalkJS\Api\UserApi;
use Symfony\Component\HttpClient\HttpClient;

final class TalkJSClient
{
    public UserApi $users;

    public ConversationApi $conversations;

    public MessageApi $messages;

    public function __construct(string $appId, string $secretKey)
    {
        $httpClient = HttpClient::create([
            'base_uri' => 'https://api.talkjs.com/v1/'.$appId.'/',
            'auth_bearer' => $secretKey,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
        ]);
        $this->users = new UserApi($httpClient);
        $this->conversations = new ConversationApi($httpClient);
        $this->messages = new MessageApi($httpClient);
    }
}
