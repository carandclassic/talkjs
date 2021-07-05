<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Models;

use CarAndClassic\TalkJS\Enumerations\MessageType;

class Message
{
    /**
     * @var string|int
     */
    public $id;

    public string $type;

    public string $conversationId;

    public ?string $senderId;

    public string $text;

    public array $readBy;

    public string $origin;

    public ?string $location;

    public array $custom;

    public \DateTimeImmutable $createdAt;

    public ?string $attachment;

    public static function createFromArray(array $data): self
    {
        $timestamp = round($data['createdAt'] / 1000, 0);

        $message = new self();
        $message->id = $data['id'];
        $message->type = $data['type'];
        $message->senderId = $data['senderId'] ?? null;
        $message->conversationId = $data['conversationId'];
        $message->text = $data['text'];
        $message->readBy = $data['readBy'];
        $message->origin = $data['origin'];
        $message->location = $data['location'] ?? null;
        $message->custom = $data['custom'];
        $message->createdAt = new \DateTimeImmutable("@$timestamp");
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
