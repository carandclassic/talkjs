<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJS\Tests\Unit;

use CarAndClassic\TalkJS\Api\UserApi;
use CarAndClassic\TalkJS\Models\User;
use CarAndClassic\TalkJS\Models\UserCreatedOrUpdated;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class UserTest extends TestCase
{
    private string $userId;

    private array $userDetails;

    protected function setUp(): void
    {
        $this->userId = 'testUserId';
        $this->userDetails = [
            'name' => 'Test User',
            'email' => ['testuser@example.com'],
            'welcomeMessage' => 'Test Welcome Message',
            'photoUrl' => null,
            'role' => 'user',
            'phone' => [],
            'custom' => [
                'test' => 'test'
            ],
        ];
    }

    public function testCreateOrUpdate(): void
    {
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode(['data' => []]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            UserApi::class
        );
        $userCreatedOrUpdated = $api->createOrUpdate($this->userId, $this->userDetails);
        $this->assertInstanceOf(UserCreatedOrUpdated::class, $userCreatedOrUpdated);
        $this->assertTrue($this->userId === $userCreatedOrUpdated->id);
        foreach($this->userDetails as $key => $value)
        {
            $this->assertTrue($value === $userCreatedOrUpdated->$key);
        }
    }

    public function testGet(): void
    {
        $this->userDetails['createdAt'] = time() * 1000;
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode(['data' => [$this->userDetails]]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            UserApi::class
        );
        $user = $api->get($this->userId);
        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($this->userId === $user->id);
        foreach($this->userDetails as $key => $value)
        {
            $this->assertTrue($value === $user->$key);
        }
    }
}
