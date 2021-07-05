<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Models;

class UserCreatedOrUpdated
{
    /**
     * @var string|int
     */
    public $id;

    public string $name;

    public array $email;

    public ?string $welcomeMessage;

    public ?string $photoUrl;

    public string $role;

    public array $phone;

    public array $custom;

    public static function createFromArray($id, array $params): UserCreatedOrUpdated
    {
        $userCreatedOrUpdated = new UserCreatedOrUpdated();
        $userCreatedOrUpdated->id = $id;
        $userCreatedOrUpdated->name = $params['name'];
        $userCreatedOrUpdated->email = $params['email'] ?? [];
        $userCreatedOrUpdated->welcomeMessage = $params['welcomeMessage'] ?? null;
        $userCreatedOrUpdated->photoUrl = $params['photoUrl'] ?? null;
        $userCreatedOrUpdated->role = $params['role'];
        $userCreatedOrUpdated->phone = $params['phone'] ?? [];
        $userCreatedOrUpdated->custom = $params['custom'] ?? [];
        return $userCreatedOrUpdated;
    }
}
