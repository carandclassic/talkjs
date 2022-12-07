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

    public bool $isLastMessageRead;

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
        $this->isLastMessageRead = $this->checkIsLastMessageRead($data);
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

    public function isLastMessageReadBy(string $userId): bool
    {
        if ($userId === $this->senderId) {
            return true;
        }

        return in_array($userId, $this->readBy, true);
    }

    public function unreadByPartisipants(): array
    {
        $readBy = [...$this->lastMessage->readBy, $this->senderId];
        $partisipants = array_keys($this->participants);

        return array_diff($partisipants, $readBy);
    }

    private function checkIsLastMessageRead(array $data): bool
    {
        return !empty($data['lastMessage']['readBy']);
    }
}
