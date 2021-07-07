<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Api;

use CarAndClassic\TalkJS\Events\UserCreatedOrUpdated;
use CarAndClassic\TalkJS\Exceptions\Api\BadRequestException;
use CarAndClassic\TalkJS\Exceptions\Api\NotFoundException;
use CarAndClassic\TalkJS\Exceptions\Api\TooManyRequestsException;
use CarAndClassic\TalkJS\Exceptions\Api\UnauthorizedException;
use CarAndClassic\TalkJS\Exceptions\Api\UnknownErrorException;
use CarAndClassic\TalkJS\Models\Conversation;
use CarAndClassic\TalkJS\Models\User;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserApi extends TalkJSApi
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
    public function createOrUpdate(string $id, array $params): UserCreatedOrUpdated
    {
        $data = $this->parseResponseData($this->httpPut("users/$id", $params));

        return new UserCreatedOrUpdated($id, $params);
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
    public function get(array $filters): array
    {
        $data = $this->parseResponseData($this->httpGet("users/", $filters));

        return User::createManyFromArray($data['data']);
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
    public function find(string $id): User
    {
        $data = $this->parseResponseData($this->httpGet("users/$id"));

        return new User($data);
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
    public function getConversations(string $id): array
    {
        $data = $this->parseResponseData($this->httpGet("users/$id/conversations/"));

        return Conversation::createManyFromArray($data['data']);
    }
}
