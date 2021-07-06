<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Tests\Feature;

use CarAndClassic\TalkJS\Api\UserApi;
use CarAndClassic\TalkJS\Models\Conversation;
use CarAndClassic\TalkJS\Models\User;
use CarAndClassic\TalkJS\Models\UserCreatedOrUpdated;
use Symfony\Component\HttpClient\Response\MockResponse;

final class UserTest extends TestCase
{
    private string $userId;

    private array $userDetails;

    private array $userConversations;

    protected function setUp(): void
    {
        parent::setUp();
        $createdAt = time() * 1000;
        $this->userId = 'testUserId';
        $this->userDetails = [
            'id' => $this->userId,
            'name' => 'Test User',
            'welcomeMessage' => 'Test Welcome Message',
            'photoUrl' => null,
            'headerPhotoUrl' => null,
            'role' => 'user',
            'email' => ['testuser@example.com'],
            'phone' => ['+11234567890'],
            'custom' => ['test' => 'test'],
            'availabilityText' => 'testAvailable',
            'locale' => 'GB',
            'createdAt' => $createdAt
        ];
        $this->userConversations = [
            [
                'id' => 'testConversationId1',
                'subject' => 'Test Conversation 1',
                'topicId' => 'Test Topic 1',
                'photoUrl' => null,
                'welcomeMessages' => ['Test Welcome Message'],
                'custom' => ['test' => 'test'],
                'participants' => [
                    $this->userId => [
                        'access' => 'ReadWrite',
                        'notify' => true
                    ],
                    $this->userId . '2' => [
                        'access' => 'Read',
                        'notify' => false
                    ]
                ],
                'createdAt' => $createdAt
            ],
            [
                'id' => 'testConversationId2',
                'subject' => 'Test Conversation 2',
                'topicId' => 'Test Topic 2',
                'photoUrl' => null,
                'welcomeMessages' => ['Test Welcome Message'],
                'custom' => ['test' => 'test'],
                'participants' => [
                    $this->userId => [
                        'access' => 'ReadWrite',
                        'notify' => true
                    ],
                    $this->userId . '2' => [
                        'access' => 'Read',
                        'notify' => false
                    ]
                ],
                'createdAt' => $createdAt
            ]
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
        unset($this->userDetails['createdAt']); // Not available in this API call

        $this->assertInstanceOf(UserCreatedOrUpdated::class, $userCreatedOrUpdated);
        $this->assertTrue($this->userId === $userCreatedOrUpdated->id);
        foreach($this->userDetails as $key => $value)
        {
            $this->assertEquals($value, $userCreatedOrUpdated->$key);
        }
    }

    public function testGet(): void
    {
        $firstUser = $this->userDetails;
        $secondUser = unserialize(serialize($this->userDetails)); // Clone array
        $secondUser['id'] = $this->userId . '2';
        $secondUser['createdAt'] += 1000;
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode(['data' => [$firstUser, $secondUser]]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            UserApi::class
        );

        $users = $api->get($this->defaultFilters);

        foreach ($users as $user) {
            $this->assertInstanceOf(User::class, $user);
        }
        foreach ($firstUser as $key => $value) {
            $this->assertEquals($value, $users[$firstUser['id']]->$key);
        }
        foreach ($secondUser as $key => $value) {
            $this->assertEquals($value, $users[$secondUser['id']]->$key);
        }
    }

    public function testFind(): void
    {
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode(['data' => [$this->userDetails]]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            UserApi::class
        );

        $user = $api->find($this->userId);

        $this->assertInstanceOf(User::class, $user);
        foreach($this->userDetails as $key => $value)
        {
            $this->assertEquals($value, $user->$key);
        }
    }

    public function testGetConversations(): void
    {
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode(['data' => $this->userConversations]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            UserApi::class
        );

        $conversations = $api->getConversations($this->userId);

        foreach ($conversations as $conversation) {
            $this->assertInstanceOf(Conversation::class, $conversation);
            foreach ($conversation as $key => $value) {
                $this->assertEquals($value, $conversation->$key);
            }
        }
    }
}
