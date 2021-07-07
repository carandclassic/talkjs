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

    public array $email;

    public array $phone;

    public array $custom;

    public ?string $availabilityText;

    public ?string $locale;

    public int $createdAt;

    public function __construct(array $data)
    {
        $this->id = (string)$data['id'];
        $this->name = $data['name'];
        $this->welcomeMessage = $data['welcomeMessage'] ?? null;
        $this->photoUrl = $data['photoUrl'] ?? null;
        $this->headerPhotoUrl = $data['headerPhotoUrl'] ?? null;
        $this->role = $data['role'] ?? null;
        $this->email = $data['email'] ?? [];
        $this->phone = $data['phone'] ?? [];
        $this->custom = $data['custom'] ?? [];
        $this->availabilityText = $data['availabilityText'] ?? null;
        $this->locale = $data['locale'] ?? null;
        $this->createdAt = $data['createdAt'];
    }

    public static function createManyFromArray(array $data): array
    {
        $users = [];
        foreach ($data as $user) {
            $users[$user['id']] = new self($user);
        }
        return $users;
    }
}
