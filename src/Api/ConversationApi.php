<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Api;

use CarAndClassic\TalkJS\Events\ConversationCreatedOrUpdated;
use CarAndClassic\TalkJS\Events\ConversationDeleted;
use CarAndClassic\TalkJS\Events\ConversationJoined;
use CarAndClassic\TalkJS\Events\ConversationLeft;
use CarAndClassic\TalkJS\Events\ConversationRead;
use CarAndClassic\TalkJS\Events\ParticipationUpdated;
use CarAndClassic\TalkJS\Exceptions\Api\BadRequestException;
use CarAndClassic\TalkJS\Exceptions\Api\NotFoundException;
use CarAndClassic\TalkJS\Exceptions\Api\TooManyRequestsException;
use CarAndClassic\TalkJS\Exceptions\Api\UnauthorizedException;
use CarAndClassic\TalkJS\Exceptions\Api\UnknownErrorException;
use CarAndClassic\TalkJS\Models\Conversation;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ConversationApi extends TalkJSApi
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
    public function createOrUpdate(string $id, array $params = []): ConversationCreatedOrUpdated
    {
        $data = $this->parseResponseData($this->httpPut("conversations/$id", $params));

        return ConversationCreatedOrUpdated::createFromArray($id, $params);
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
    public function get(array $filters = []): array
    {
        $data = $this->parseResponseData($this->httpGet('conversations', $filters));

        return Conversation::createManyFromArray($data['data']);
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
    public function find(string $id): Conversation
    {
        $data = $this->parseResponseData($this->httpGet("conversations/$id"));

        return Conversation::createFromArray($data['data'][0]);
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
    public function markAsReadBy(string $conversationId, string $userId): ConversationRead
    {
        $data = $this->parseResponseData($this->httpPost("conversations/$conversationId/readBy/$userId"));

        return new ConversationRead($conversationId, $userId);
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
    public function delete(string $id): ConversationDeleted
    {
        $data = $this->parseResponseData($this->httpDelete("conversations/$id"));

        return new ConversationDeleted();
    }
}
