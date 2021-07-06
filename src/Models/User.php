<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Models;

class User
{
    public string $id;
    
    public string $name;

    public ?string $welcomeMessage;

    public ?string $photoUrl;

    public ?string $headerPhotoUrl;

    public ?string $role;

    public ?array $email;

    public ?array $phone;

    public ?array $custom;

    public ?string $availabilityText;

    public ?string $locale;

    public int $createdAt;

    public static function createFromArray(array $data): User
    {
        $user = new self();
        $user->id = (string)$data['id'];
        $user->name = $data['name'];
        $user->welcomeMessage = $data['welcomeMessage'] ?? null;
        $user->photoUrl = $data['photoUrl'] ?? null;
        $user->headerPhotoUrl = $data['headerPhotoUrl'] ?? null;
        $user->role = $data['role'] ?? null;
        $user->email = $data['email'] ?? [];
        $user->phone = $data['phone'] ?? [];
        $user->custom = $data['custom'] ?? [];
        $user->availabilityText = $data['availabilityText'] ?? null;
        $user->locale = $data['locale'] ?? null;
        $user->createdAt = $data['createdAt'];

        return $user;
    }

    public static function createManyFromArray(array $data): array
    {
        $users = [];
        foreach ($data as $user) {
            $users[$user['id']] = self::createFromArray($user);
        }
        return $users;
    }
}
