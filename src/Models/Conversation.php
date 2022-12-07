<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Models;

use CarAndClassic\TalkJS\Models\Message;

class Conversation
{
    public string $id;

    public ?string $subject;

    public ?string $topicId;

    public ?string $photoUrl;

    public array $welcomeMessages;

    public array $custom;

    public ?Message $lastMessage;

    public array $participants;

    public int $createdAt;

    public function __construct(array $data)
    {
        $this->id = (string)$data['id'];
        $this->subject = $data['subject'] ?? null;
        $this->topicId = (string)$data['topicId'] ?? null;
        $this->photoUrl = $data['photoUrl'] ?? null;
        $this->welcomeMessages = $data['welcomeMessages'] ?? [];
        $this->custom = $data['custom'] ?? [];
        $this->lastMessage = isset($data['lastMessage']) ? new Message($data['lastMessage']) : null;
        $this->participants = $data['participants'] ?? [];
        $this->createdAt = $data['createdAt'];
    }

    public static function createManyFromArray(array $data): array
    {
        $conversations = [];
        foreach ($data as $conversation) {
            $conversations[$conversation['id']] = new self($conversation);
        }
        return $conversations;
    }

    public function unreadBy(): ?array
    {
        if ($this->lastMessage === null) {
            return null;
        }

        $readBy = $this->lastMessage->readBy;
        $readBy[] = $this->lastMessage->senderId;
        $participants = array_keys($this->participants);

        return array_diff($participants, $readBy);
    }
}
