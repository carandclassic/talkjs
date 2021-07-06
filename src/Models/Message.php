<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Models;

use CarAndClassic\TalkJS\Enumerations\MessageType;

class Message
{
    public string $id;

    public string $type;

    public string $conversationId;

    public ?string $sender;

    public string $text;

    public array $readBy;

    public string $origin;

    public ?string $location;

    public array $custom;

    public ?string $attachment;

    public int $createdAt;

    public static function createFromArray(array $data): self
    {
        $message = new self();
        $message->id = (string)$data['id'];
        $message->type = $data['type'];
        $message->sender = $data['sender'] ?? null;
        $message->conversationId = (string)$data['conversationId'];
        $message->text = $data['text'];
        $message->readBy = $data['readBy'];
        $message->origin = $data['origin'];
        $message->location = $data['location'] ?? null;
        $message->custom = $data['custom'];
        $message->createdAt = $data['createdAt'];
        $message->attachment = $data['attachment'] ?? null;

        return $message;
    }

    public static function createManyFromArray(array $data): array
    {
        $messages = [];
        foreach ($data as $message) {
            $messages[] = self::createFromArray($message);
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
