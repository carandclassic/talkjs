<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJS\Api;

use CarAndClassic\TalkJS\Exception;
use CarAndClassic\TalkJS\Model;

final class UserApi extends TalkJSApi
{
    /**
     * @throws Exception
     */
    public function createOrUpdate(string $id, array $params)
    {
        $response = $this->httpPut("users/$id", $params);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\User\UserCreatedOrUpdated::class);
    }

    /**
     * @throws Exception
     */
    public function get(string $id)
    {
        $response = $this->httpGet("users/$id");

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\User\User::class);
    }
}
