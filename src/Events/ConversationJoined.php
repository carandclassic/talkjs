<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Events;

class ConversationJoined extends ConversationEvent
{
    public string $access;

    public bool $notify;

    public function __construct(string $conversationId, string $messageId, string $access, bool $notify = true)
    {
        $this->access = $access;
        $this->notify = $notify;
        parent::__construct($conversationId, $messageId);
    }
}
