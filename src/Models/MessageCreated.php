<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Models;

use CarAndClassic\TalkJS\Enumerations\MessageType;

class MessageCreated
{
    public string $type;

    public ?string $sender;

    public string $text;

    public array $custom;

    public function __construct(string $type, ?string $sender, string $text, array $custom)
    {
        $this->type = $type;
        $this->sender = $sender;
        $this->text = $text;
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
