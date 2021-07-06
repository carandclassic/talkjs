<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Events;

class ConversationCreatedOrUpdated
{
    public string $id;

    public array $participants;

    public string $subject;

    public array $welcomeMessages;

    public array $custom;

    public ?string $photoUrl;
    
    public function __construct(string $id, array $data)
    {
        $this->id = $id;
        $this->participants = $data['participants'] ?? [];
        $this->subject = $data['subject'];
        $this->welcomeMessages = $data['welcomeMessages'] ?? [];
        $this->custom = $data['custom'] ?? [];
        $this->photoUrl = $data['photoUrl'] ?? null;
    }
}
