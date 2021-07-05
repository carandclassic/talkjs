<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJS\Api;

use CarAndClassic\TalkJS\Models\User;
use CarAndClassic\TalkJS\Models\UserCreatedOrUpdated;
use Exception;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserApi extends TalkJSApi
{
    /**
     * @throws Exception|TransportExceptionInterface
     */
    public function createOrUpdate(string $id, array $params): UserCreatedOrUpdated
    {
        $data = $this->parseResponseData($this->httpPut("users/$id", $params));

        return UserCreatedOrUpdated::createFromArray($id, $params);
    }

    /**
     * @throws Exception|TransportExceptionInterface
     */
    public function get(string $id): User
    {
        $data = $this->parseResponseData($this->httpGet("users/$id"));

        return User::createFromArray($data['data']);
    }
}
