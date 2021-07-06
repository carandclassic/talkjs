<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Events;

use CarAndClassic\TalkJS\Enumerations\ConversationPermission;

class ParticipationUpdated
{
    public string $conversationId;

    public string $userId;

    public string $access;

    public bool $notify;
    
    public function __construct(string $conversationId, string $userId, array $data)
    {
        $this->conversationId = $conversationId;
        $this->userId = $userId;
        $this->access = $data['access'] ?? ConversationPermission::READ_WRITE;
        $this->notify = $data['notify'] ?? true;
    }
}
