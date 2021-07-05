<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Models;

class User
{
    /**
     * @var string|int
     */
    public $id;
    
    public string $name;

    public string $welcomeMessage;

    public string $photoUrl;

    public string $role;

    public array $email;

    public array $phone;

    public array $custom;

    public string $availabilityText;

    public string $locale;

    public \DateTimeImmutable $createdAt;

    public static function createFromArray(array $data): User
    {
        $timestamp = round($data['createdAt'] / 1000, 0);

        $user = new self();
        $user->id = $data['id'];
        $user->name = $data['name'];
        $user->welcomeMessage = $data['welcomeMessage'];
        $user->photoUrl = $data['photoUrl'];
        $user->role = $data['role'];
        $user->email = $data['email'] ?? [];
        $user->phone = $data['phone'] ?? [];
        $user->custom = $data['custom'] ?? [];
        $user->availabilityText = $data['availabilityText'];
        $user->locale = $data['locale'];
        $user->createdAt = new \DateTimeImmutable("@$timestamp");

        return $user;
    }
}
