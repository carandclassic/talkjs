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
    
    public function __construct(string $id, array $data)
    {
        $this->id = $id;
        $this->name = $data['name'];
        $this->welcomeMessage = $data['welcomeMessage'] ?? null;
        $this->photoUrl = $data['photoUrl'] ?? null;
        $this->headerPhotoUrl = $data['headerPhotoUrl'] ?? null;
        $this->role = $data['role'];
        $this->email = $data['email'] ?? [];
        $this->phone = $data['phone'] ?? [];
        $this->custom = $data['custom'] ?? [];
        $this->availabilityText = $data['availabilityText'] ?? null;
        $this->locale = $data['locale'] ?? null;
    }
}
