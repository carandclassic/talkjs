<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJS\Api;

use CarAndClassic\TalkJS\Enumerations\MessageType;
use CarAndClassic\TalkJS\Exceptions\Api\BadRequestException;
use CarAndClassic\TalkJS\Exceptions\Api\NotFoundException;
use CarAndClassic\TalkJS\Exceptions\Api\TooManyRequestsException;
use CarAndClassic\TalkJS\Exceptions\Api\UnauthorizedException;
use CarAndClassic\TalkJS\Exceptions\Api\UnknownErrorException;
use CarAndClassic\TalkJS\Models\Message;
use CarAndClassic\TalkJS\Models\MessageCreated;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MessageApi extends TalkJSApi
{
    /**
     * @throws UnauthorizedException
     * @throws TooManyRequestsException
     * @throws UnknownErrorException
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws BadRequestException
     * @throws NotFoundException
     */
    public function get(string $conversationId, array $filters = []): array
    {
        $data = $this->parseResponseData($this->httpGet("conversations/$conversationId/messages", $filters));

        return Message::createManyFromArray($data['data']);
    }

    /**
     * @throws UnauthorizedException
     * @throws TooManyRequestsException
     * @throws UnknownErrorException
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws BadRequestException
     * @throws NotFoundException
     */
    public function find(string $conversationId, string $messageId): Message
    {
        $data = $this->parseResponseData($this->httpGet("conversations/$conversationId/messages/$messageId"));

        return Message::createFromArray($data['data']);
    }

    /**
     * @throws UnauthorizedException
     * @throws TooManyRequestsException
     * @throws UnknownErrorException
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws BadRequestException
     * @throws NotFoundException
     */
    public function createSystemMessage(string $conversationId, string $text, array $custom = []): MessageCreated
    {
        return $this->createMessage(MessageType::SYSTEM, $conversationId, $text, $custom);
    }

    /**
     * @throws UnauthorizedException
     * @throws TooManyRequestsException
     * @throws UnknownErrorException
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws BadRequestException
     * @throws NotFoundException
     */
    public function createUserMessage(string $conversationId, string $sender, string $text, array $custom = []): MessageCreated
    {
        return $this->createMessage(MessageType::USER, $conversationId, $text, $custom, $sender);
    }

    /**
     * @throws UnauthorizedException
     * @throws TooManyRequestsException
     * @throws UnknownErrorException
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws BadRequestException
     * @throws NotFoundException
     */
    private function createMessage(string $type, string $conversationId, string $text, array $custom = [], ?string $sender = null): MessageCreated
    {
        $body = [
            'type' => $type,
            'text' => $text,
            'custom' => (object) $custom,
        ];
        if ($type === MessageType::USER || isset($sender)) {
            $body['sender'] = $sender;
        }
        $data = $this->parseResponseData(
            $this->httpPost("conversations/$conversationId/messages", [$body])
        );

        return new MessageCreated($type, $sender, $text, null, $custom);
    }

    //TODO: sendFile
}
