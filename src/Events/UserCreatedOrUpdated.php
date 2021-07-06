<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Events;

class UserCreatedOrUpdated
{
    public string $id;

    public string $name;

    public array $email;

    public ?string $welcomeMessage;

    public ?string $photoUrl;

    public ?string $headerPhotoUrl;

    public ?string $role;

    public array $phone;

    public array $custom;

    public ?string $availabilityText;

    public ?string $locale;

    public static function createFromArray(string $id, array $data): UserCreatedOrUpdated
    {
        $userCreatedOrUpdated = new self();
        $userCreatedOrUpdated->id = $id;
        $userCreatedOrUpdated->name = $data['name'];
        $userCreatedOrUpdated->welcomeMessage = $data['welcomeMessage'] ?? null;
        $userCreatedOrUpdated->photoUrl = $data['photoUrl'] ?? null;
        $userCreatedOrUpdated->headerPhotoUrl = $data['headerPhotoUrl'] ?? null;
        $userCreatedOrUpdated->role = $data['role'];
        $userCreatedOrUpdated->email = $data['email'] ?? [];
        $userCreatedOrUpdated->phone = $data['phone'] ?? [];
        $userCreatedOrUpdated->custom = $data['custom'] ?? [];
        $userCreatedOrUpdated->availabilityText = $data['availabilityText'] ?? null;
        $userCreatedOrUpdated->locale = $data['locale'] ?? null;

        return $userCreatedOrUpdated;
    }
}
