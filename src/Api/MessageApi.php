<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJS\Api;

use CarAndClassic\TalkJS\Enumerations\MessageType;
use CarAndClassic\TalkJS\Models\Message;
use CarAndClassic\TalkJS\Models\MessageCreated;
use Exception;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MessageApi extends TalkJSApi
{
    /**
     * @throws Exception|TransportExceptionInterface
     */
    public function findMessages(string $conversationId, array $filters = []): array
    {
        $data = $this->parseResponseData($this->httpGet("conversations/$conversationId/messages", $filters));

        return Message::createManyFromArray($data['data']);
    }

    /**
     * @throws Exception|TransportExceptionInterface
     */
    public function postSystemMessage(string $conversationId, string $text, array $custom = []): MessageCreated
    {
        $data = $this->parseResponseData(
            $this->httpPost("conversations/$conversationId/messages", [
                [
                    'type' => 'SystemMessage',
                    'text' => $text,
                    'custom' => (object) $custom,
                ],
            ])
        );

        return new MessageCreated(MessageType::SYSTEM, null, $text, $custom);
    }

    /**
     * @throws Exception|TransportExceptionInterface
     */
    public function postUserMessage(string $conversationId, string $sender, string $text, array $custom = []): MessageCreated
    {
        $data = $this->parseResponseData(
            $this->httpPost("conversations/$conversationId/messages", [
                [
                    'type' => 'UserMessage',
                    'sender' => $sender,
                    'text' => $text,
                    'custom' => (object) $custom,
                ],
            ])
        );

        return new MessageCreated(MessageType::USER, $sender, $text, $custom);
    }
}
