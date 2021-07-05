<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJS;

use CarAndClassic\TalkJS\Api\ConversationApi;
use CarAndClassic\TalkJS\Api\MessageApi;
use CarAndClassic\TalkJS\Api\UserApi;
use Symfony\Component\HttpClient\HttpClient;

final class TalkJSClient
{
    public UserApi $userApi;

    public ConversationApi $conversationApi;

    public MessageApi $messageApi;

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
        $this->userApi = new UserApi($httpClient);
        $this->conversationApi = new ConversationApi($httpClient);
        $this->messageApi = new MessageApi($httpClient);
    }
}
