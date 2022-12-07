<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Tests\Feature;

use CarAndClassic\TalkJS\Api\ConversationApi;
use CarAndClassic\TalkJS\Enumerations\ConversationAccess;
use CarAndClassic\TalkJS\Events\ConversationCreatedOrUpdated;
use CarAndClassic\TalkJS\Events\ConversationDeleted;
use CarAndClassic\TalkJS\Events\ConversationJoined;
use CarAndClassic\TalkJS\Events\ConversationLeft;
use CarAndClassic\TalkJS\Events\ConversationRead;
use CarAndClassic\TalkJS\Events\ParticipationUpdated;
use CarAndClassic\TalkJS\Models\Conversation;
use CarAndClassic\TalkJS\Models\Message;
use Symfony\Component\HttpClient\Response\MockResponse;

final class ConversationTest extends TestCase
{
    private array $userIds;

    private array $conversations;

    public function setUp(): void
    {
        parent::setUp();
        $createdAt = time() * 1000;
        $this->userIds = [
            'TestConversationUserId1',
            'TestConversationUserId2'
        ];
        $this->conversations = [
            [
                'id' => 'testConversationId1',
                'subject' => 'Test Conversation 1',
                'topicId' => 'Test Topic 1',
                'photoUrl' => null,
                'welcomeMessages' => ['Test Welcome Message'],
                'custom' => ['test' => 'test'],
                'lastMessage' => new Message([
                    'id' => "test",
                    'type' => "UserMessage",
                    'conversationId' => "dev_test",
                    'senderId' => $this->userIds[1],
                    'text' => "This is the message copy",
                    'readBy' => [
                        $this->userIds[0],
                    ],
                    'origin' => "rest",
                    'location' => null,
                    'custom' => [],
                    'attachment' => null,
                    'createdAt' => $createdAt,
                ]),
                'participants' => [
                    $this->userIds[0] => [
                        'access' => 'ReadWrite',
                        'notify' => true
                    ],
                    $this->userIds[1] => [
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
                'lastMessage' => null,
                'participants' => [
                    $this->userIds[0] => [
                        'access' => 'ReadWrite',
                        'notify' => true
                    ],
                    $this->userIds[1] => [
                        'access' => 'Read',
                        'notify' => false
                    ]
                ],
                'createdAt' => $createdAt
            ],
            [
                'id' => 'testConversationId3',
                'subject' => 'Test Conversation 3',
                'topicId' => 'Test Topic 3',
                'photoUrl' => null,
                'welcomeMessages' => ['Test Welcome Message'],
                'custom' => ['test' => 'test'],
                'lastMessage' => new Message([
                    'id' => "test",
                    'type' => "UserMessage",
                    'conversationId' => "dev_test",
                    'senderId' => $this->userIds[1],
                    'text' => "This is the message copy",
                    'readBy' => [],
                    'origin' => "rest",
                    'location' => null,
                    'custom' => [],
                    'attachment' => null,
                    'createdAt' => $createdAt,
                ]),
                'participants' => [
                    $this->userIds[0] => [
                        'access' => 'ReadWrite',
                        'notify' => true
                    ],
                    $this->userIds[1] => [
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
        $id = 'Test Conversation Creation';
        $params = [
            'participants' => ['userId1', 'userId2'],
            'subject' => 'Test Subject',
            'welcomeMessages' => ['Test Welcome Message 1', 'Test Welcome Message 2',],
            'custom' => ['test' => 'test'],
            'photoUrl' => null,
        ];
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode(['data' => []]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            ConversationApi::class
        );

        $conversationCreatedOrUpdated = $api->createOrUpdate($id, $params);

        $this->assertInstanceOf(ConversationCreatedOrUpdated::class, $conversationCreatedOrUpdated);
        $this->assertEquals($id, $conversationCreatedOrUpdated->id);
        foreach ($params as $key => $value) {
            $this->assertEquals($value, $conversationCreatedOrUpdated->$key);
        }
    }

    public function testGet(): void
    {
        $firstConversation = $this->conversations[0];
        $secondConversation = $this->conversations[1];

        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode(['data' => $this->conversations]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            ConversationApi::class
        );

        $conversations = $api->get($this->defaultFilters);

        foreach ($conversations as $conversation) {
            $this->assertInstanceOf(Conversation::class, $conversation);
        }
        foreach ($firstConversation as $key => $value) {
            $this->assertEquals($value, $conversations[$firstConversation['id']]->$key);
        }
        foreach ($secondConversation as $key => $value) {
            $this->assertEquals($value, $conversations[$secondConversation['id']]->$key);
        }
    }

    public function testFind(): void
    {
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode($this->conversations[0]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            ConversationApi::class
        );

        $conversation = $api->find($this->conversations[0]['id']);

        $this->assertInstanceOf(Conversation::class, $conversation);

        foreach ($this->conversations[0] as $key => $value) {
            $this->assertEquals($value, $conversation->$key);
        }
    }

    public function testMarkAsReadBy(): void
    {
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode(['data' => []]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            ConversationApi::class
        );

        $conversationRead = $api->markAsReadBy($this->conversations[0]['id'], $this->userIds[0]);

        $this->assertInstanceOf(ConversationRead::class, $conversationRead);
        $this->assertEquals($this->conversations[0]['id'], $conversationRead->conversationId);
        $this->assertEquals($this->userIds[0], $conversationRead->userId);
    }

    public function testJoin(): void
    {
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode(['data' => []]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            ConversationApi::class
        );

        $conversationJoined = $api->join($this->conversations[0]['id'], $this->userIds[0], ConversationAccess::READ, false);

        $this->assertInstanceOf(ConversationJoined::class, $conversationJoined);
        $this->assertEquals($this->conversations[0]['id'], $conversationJoined->conversationId);
        $this->assertEquals($this->userIds[0], $conversationJoined->userId);
        $this->assertEquals(ConversationAccess::READ, $conversationJoined->access);
        $this->assertEquals(false, $conversationJoined->notify);
    }

    public function testUpdateParticipation(): void
    {
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode(['data' => []]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            ConversationApi::class
        );

        $participationUpdated = $api->updateParticipation(
            $this->conversations[0]['id'],
            $this->userIds[0],
            ConversationAccess::READ,
            false
        );

        $this->assertInstanceOf(ParticipationUpdated::class, $participationUpdated);
        $this->assertEquals($this->conversations[0]['id'], $participationUpdated->conversationId);
        $this->assertEquals($this->userIds[0], $participationUpdated->userId);
        $this->assertEquals(ConversationAccess::READ, $participationUpdated->access);
        $this->assertEquals(false, $participationUpdated->notify);
    }

    public function testLeave(): void
    {
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode(['data' => []]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            ConversationApi::class
        );

        $conversationLeft = $api->leave($this->conversations[0]['id'], $this->userIds[0]);

        $this->assertInstanceOf(ConversationLeft::class, $conversationLeft);
        $this->assertEquals($this->conversations[0]['id'], $conversationLeft->conversationId);
        $this->assertEquals($this->userIds[0], $conversationLeft->userId);
    }

    public function testDelete(): void
    {
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode(['data' => []]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            ConversationApi::class
        );

        $conversationDeleted = $api->delete($this->conversations[0]['id']);

        $this->assertInstanceOf(ConversationDeleted::class, $conversationDeleted);
    }

    public function testUnreadBy()
    {
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode(['data' => $this->conversations]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            ConversationApi::class
        );

        $conversations = $api->get($this->defaultFilters);
        $conversation1 = current($conversations);
        $conversation2 = next($conversations);
        $conversation3 = next($conversations);

        $this->assertEmpty($conversation1->unreadBy());
        $this->assertNull($conversation2->unreadBy());
        $this->assertEquals($conversation3->unreadBy(), ['TestConversationUserId1']);
    }
}
