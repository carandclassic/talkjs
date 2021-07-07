<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Api;

use CarAndClassic\TalkJS\Enumerations\ConversationAccess;
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

        return new ConversationCreatedOrUpdated($id, $params);
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

        return new Conversation($data);
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
    public function join(string $conversationId, string $userId, ?string $access = null, bool $notify = true): ConversationJoined
    {
        $access ??= ConversationAccess::READ_WRITE;
        $data = $this->parseResponseData(
            $this->httpPut("conversations/$conversationId/participants/$userId", ['access' => $access, 'notify' => $notify])
        );

        return new ConversationJoined($conversationId, $userId, $access, $notify);
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
    public function updateParticipation(string $conversationId, string $userId, ?string $access = null, bool $notify = true): ParticipationUpdated
    {
        $access ??= ConversationAccess::READ_WRITE;
        $data = $this->parseResponseData(
            $this->httpPatch("conversations/$conversationId/participants/$userId", ['access' => $access, 'notify' => $notify])
        );

        return new ParticipationUpdated($conversationId, $userId, $access, $notify);
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
    public function leave(string $conversationId, string $userId): ConversationLeft
    {
        $data = $this->parseResponseData($this->httpDelete("conversations/$conversationId/participants/$userId"));

        return new ConversationLeft($conversationId, $userId);
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
