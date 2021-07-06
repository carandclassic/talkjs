<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Models;

class Conversation
{
    public string $id;

    public ?string $subject;

    public ?string $topicId;

    public ?string $photoUrl;

    public ?array $welcomeMessages;

    public ?array $custom;

    public array $participants;

    public int $createdAt;

    public static function createFromArray(array $data): Conversation
    {
        $user = new self();
        $user->id = (string)$data['id'];
        $user->subject = $data['subject'] ?? null;
        $user->topicId = (string)$data['topicId'] ?? null;
        $user->photoUrl = $data['photoUrl'] ?? null;
        $user->welcomeMessages = $data['welcomeMessages'] ?? null;
        $user->custom = $data['custom'] ?? [];
        $user->participants = $data['participants'] ?? [];
        $user->createdAt = $data['createdAt'];

        return $user;
    }

    public static function createManyFromArray(array $data): array
    {
        $conversations = [];
        foreach ($data as $conversation) {
            $conversations[$conversation['id']] = self::createFromArray($conversation);
        }
        return $conversations;
    }
}
