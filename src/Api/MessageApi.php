<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Api;

use CarAndClassic\TalkJS\Enumerations\MessageType;
use CarAndClassic\TalkJS\Events\MessageCreated;
use CarAndClassic\TalkJS\Events\MessageDeleted;
use CarAndClassic\TalkJS\Events\MessageEdited;
use CarAndClassic\TalkJS\Exceptions\Api\BadRequestException;
use CarAndClassic\TalkJS\Exceptions\Api\NotFoundException;
use CarAndClassic\TalkJS\Exceptions\Api\TooManyRequestsException;
use CarAndClassic\TalkJS\Exceptions\Api\UnauthorizedException;
use CarAndClassic\TalkJS\Exceptions\Api\UnknownErrorException;
use CarAndClassic\TalkJS\Models\Message;
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
        return $this->createMessage(MessageType::SYSTEM, $conversationId, $text, null, $custom);
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
        return $this->createMessage(MessageType::USER, $conversationId, $text, $sender, $custom);
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
    private function createMessage(string $type, string $conversationId, string $text, ?string $sender = null, array $custom = []): MessageCreated
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

    public function edit(string $conversationId, string $messageId, string $text, array $custom): MessageEdited
    {
        $data = $this->parseResponseData(
            $this->httpPut("conversations/$conversationId/messages/$messageId", ['text' => $text, 'custom' => $custom])
        );

        return new MessageEdited($conversationId, $messageId, $text, $custom);
    }

    //TODO: sendFile

    public function delete(string $conversationId, string $messageId): MessageDeleted
    {
        $data = $this->parseResponseData(
            $this->httpDelete("conversations/$conversationId/messages/$messageId")
        );

        return new MessageDeleted();
    }
}
