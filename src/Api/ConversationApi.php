<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJS\Api;

use CarAndClassic\TalkJS\Models\Conversation;
use CarAndClassic\TalkJS\Models\ConversationCreatedOrUpdated;
use CarAndClassic\TalkJS\Models\ConversationJoined;
use CarAndClassic\TalkJS\Models\ConversationLeft;
use CarAndClassic\TalkJS\Models\ParticipationUpdated;
use Exception;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ConversationApi extends TalkJSApi
{
    /**
     * @throws Exception|TransportExceptionInterface
     */
    public function createOrUpdate(string $id, array $params): ConversationCreatedOrUpdated
    {
        $data = $this->parseResponseData($this->httpPut("conversations/$id", $params));

        return new ConversationCreatedOrUpdated();
    }

    /**
     * @throws Exception|TransportExceptionInterface
     */
    public function get(string $id): Conversation
    {
        $data = $this->parseResponseData($this->httpGet("conversations/$id"));

        return Conversation::createFromArray($data['data']);
    }

    /**
     * @throws Exception|TransportExceptionInterface
     */
    public function find(array $filters = []): array
    {
        $data = $this->parseResponseData($this->httpGet('conversations', $filters));

        return Conversation::createManyFromArray($data['data']);
    }

    /**
     * @throws Exception|TransportExceptionInterface
     */
    public function join(string $conversationId, string $userId, array $params = []): ConversationJoined
    {
        $data = $this->parseResponseData(
            $this->httpPut("conversations/$conversationId/participants/$userId", $params)
        );

        return new ConversationJoined();
    }

    /**
     * @throws Exception|TransportExceptionInterface
     */
    public function updateParticipation(string $conversationId, string $userId, array $params = []): ParticipationUpdated
    {
        $data = $this->parseResponseData(
            $this->httpPatch("conversations/$conversationId/participants/$userId", $params)
        );

        return new ParticipationUpdated();
    }

    /**
     * @throws Exception|TransportExceptionInterface
     */
    public function leave(string $conversationId, string $userId): ConversationLeft
    {
        $data = $this->parseResponseData($this->httpDelete("conversations/$conversationId/participants/$userId"));

        return new ConversationLeft();
    }
}
