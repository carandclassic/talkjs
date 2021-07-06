<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Events;

class ParticipationUpdated
{
    public string $conversationId;

    public string $userId;

    public string $access;

    public bool $notify;
    
    public function __construct(string $conversationId, string $userId, string $access, bool $notify)
    {
        $this->conversationId = $conversationId;
        $this->userId = $userId;
        $this->access = $access;
        $this->notify = $notify;
    }
}
