<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Events;

use CarAndClassic\TalkJS\Enumerations\MessageType;

class MessageCreated
{
    public string $type;

    public ?string $senderId;

    public string $text;

    public ?string $attachmentToken;

    public array $custom;

    public function __construct(string $type, ?string $senderId, string $text, ?string $attachmentToken = null, $custom = [])
    {
        $this->type = $type;
        $this->senderId = $senderId;
        $this->text = $text;
        $this->attachmentToken = $attachmentToken;
        $this->custom = $custom;
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
