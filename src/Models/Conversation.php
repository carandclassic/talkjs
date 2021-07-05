<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Models;

class Conversation implements CreatableFromArray
{
    /**
     * @var string|int
     */
    public $id;

    public string $subject;

    public string $topicId;

    public string $photoUrl;

    public array $welcomeMessages;

    public array $custom;

    public array $participants;

    public \DateTimeImmutable $createdAt;

    /**
     * {@inheritdoc}
     */
    public static function createFromArray(array $data)
    {
        $timestamp = round($data['createdAt'] / 1000, 0);

        $user = new self();
        $user->id = $data['id'];
        $user->subject = $data['subject'];
        $user->topicId = $data['topicId'];
        $user->photoUrl = $data['photoUrl'];
        $user->welcomeMessages = $data['welcomeMessages'];
        $user->custom = $data['custom'] ?? [];
        $user->participants = $data['participants'] ?? [];
        $user->createdAt = new \DateTimeImmutable("@$timestamp");

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
