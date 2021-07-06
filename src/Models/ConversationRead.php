<?php


namespace CarAndClassic\TalkJS\Models;


class ConversationRead
{
    public string $conversationId;

    public string $userId;

    public function __construct(string $conversationId, string $userId)
    {
        $this->conversationId = $conversationId;
        $this->userId = $userId;
    }
}
