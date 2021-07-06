<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Events;

abstract class ConversationEvent
{
    public string $conversationId;

    public string $userId;

    public function __construct(string $conversationId, string $userId)
    {
        $this->conversationId = $conversationId;
        $this->userId = $userId;
    }
}
