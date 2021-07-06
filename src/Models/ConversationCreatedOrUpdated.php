<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Models;

class ConversationCreatedOrUpdated
{
    public string $id;

    public array $participants;

    public string $subject;

    public ?array $welcomeMessages;

    public ?array $custom;

    public ?string $photoUrl;

    public static function createFromArray(string $id, array $data): ConversationCreatedOrUpdated
    {
        $conversationUpdatedOrCreated = new self();
        $conversationUpdatedOrCreated->id = $id;
        $conversationUpdatedOrCreated->participants = $data['participants'] ?? [];
        $conversationUpdatedOrCreated->subject = $data['subject'];
        $conversationUpdatedOrCreated->welcomeMessages = $data['welcomeMessages'] ?? [];
        $conversationUpdatedOrCreated->custom = $data['custom'] ?? [];
        $conversationUpdatedOrCreated->photoUrl = $data['photoUrl'] ?? null;
        return $conversationUpdatedOrCreated;
    }
}
