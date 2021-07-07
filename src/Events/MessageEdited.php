<?php

namespace CarAndClassic\TalkJS\Events;

class MessageEdited
{
    public string $conversationId;

    public string $messageId;

    public string $text;

    public array $custom;

    public function __construct(string $conversationId, string $messageId, string $text, array $custom = [])
    {
        $this->conversationId = $conversationId;
        $this->messageId = $messageId;
        $this->text = $text;
        $this->custom = $custom;
    }
}
