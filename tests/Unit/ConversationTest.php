<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Tests\Unit;

use CarAndClassic\TalkJS\Models\Conversation;
use CarAndClassic\TalkJS\Tests\TestCase;

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
                'lastMessage' => [
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
                ],
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
                'lastMessage' => [
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
                ],
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

    public function testCreateManyFromArray(): void
    {
        $conversations = Conversation::createManyFromArray($this->conversations);

        $this->assertIsArray($conversations);

        foreach ($conversations as $conversation) {
            $this->assertInstanceOf(Conversation::class, $conversation);
        }
    }

    public function testUnreadBy()
    {
        $conversation1 = new Conversation($this->conversations[0]);
        $conversation2 = new Conversation($this->conversations[1]);
        $conversation3 = new Conversation($this->conversations[2]);

        $this->assertEmpty($conversation1->unreadBy());
        $this->assertNull($conversation2->unreadBy());
        $this->assertEquals($conversation3->unreadBy(), ['TestConversationUserId1']);
    }
}
