<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Models;

use CarAndClassic\TalkJS\Enumerations\MessageType;

class Message
{
    public string $id;
    public string $type;
    public string $conversationId;
    public ?string $senderId;
    public string $text;
    public array $readBy;
    public string $origin;
    public ?string $location;
    public array $custom;
    public ?array $attachment;
    public int $createdAt;

    public function __construct(array $data)
    {
        $this->id = (string)$data['id'];
        $this->type = $data['type'];
        $this->senderId = $data['senderId'] ?? null;
        $this->conversationId = (string)$data['conversationId'];
        $this->text = $data['text'];
        $this->readBy = $data['readBy'];
        $this->origin = $data['origin'];
        $this->location = $data['location'] ?? null;
        $this->custom = $data['custom'];
        $this->attachment = $data['attachment'] ?? null;
        $this->createdAt = $data['createdAt'];
    }

    public static function createManyFromArray(array $data): array
    {
        $messages = [];
        foreach ($data as $message) {
            $messages[$message['id']] = new self($message);
        }
        return $messages;
    }

    public function isUserMessage(): bool
    {
        return $this->type == MessageType::USER;
    }

    public function isSystemMessage(): bool
    {
        return $this->type == MessageType::SYSTEM;
    }
}
