<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Tests\Unit;

use CarAndClassic\TalkJS\Enumerations\MessageType;
use CarAndClassic\TalkJS\Models\Message;
use CarAndClassic\TalkJS\Tests\TestCase;

final class MessageTest extends TestCase
{
    private string $conversationId;

    private string $senderId;

    private array $messages;

    private Message $message1;

    private Message $message2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->conversationId = 'testConversationId';
        $this->senderId = 'testSenderId';
        $this->userIds = [
            'TestConversationUserId1',
            'TestConversationUserId2'
        ];
        $this->messages = [
            [
                'id' => '1', // At time of writing results are returned descending
                'type' => MessageType::USER,
                'conversationId' => $this->conversationId,
                'senderId' => $this->senderId,
                'text' => 'Test User Message',
                'readBy' => [
                    $this->userIds[0]
                ],
                'origin' => 'rest',
                'location' => null,
                'custom' => ['test' => 'test'],
                'createdAt' => (time() + 1) * 1000, // At time of writing TalkJS returns timestamp in milliseconds
                'attachment' => null
            ],
            [
                'id' => '2',
                'type' => MessageType::SYSTEM,
                'conversationId' => $this->conversationId,
                'senderId' => null,
                'text' => 'Test System Message',
                'readBy' => [],
                'origin' => 'rest',
                'location' => null,
                'custom' => ['test' => 'test'],
                'createdAt' => time() * 1000,
                'attachment' => null
            ]
        ];
        $this->message1 = new Message($this->messages[0]);
        $this->message2 = new Message($this->messages[1]);
    }

    public function testCreateManyFromArray(): void
    {
        $messages = Message::createManyFromArray($this->messages);

        $this->assertIsArray($messages);

        foreach ($messages as $message) {
            $this->assertInstanceOf(Message::class, $message);
        }
    }

    public function testIsUserMessage(): void
    {
        $this->assertSame($this->message1->isUserMessage(), true);
        $this->assertSame($this->message2->isUserMessage(), false);
    }

    public function testIsSystemMessage(): void
    {
        $this->assertSame($this->message1->isSystemMessage(), false);
        $this->assertSame($this->message2->isSystemMessage(), true);
    }

    public function testIsReadBy(): void
    {
        $this->assertSame($this->message1->isReadBy($this->userIds[0]), true);
        $this->assertSame($this->message1->isReadBy($this->userIds[1]), false);
        $this->assertSame($this->message1->isReadBy($this->senderId), true);
        $this->assertSame($this->message2->isReadBy($this->userIds[0]), false);
    }

    public function testIsRead(): void
    {
        $this->assertSame($this->message1->isRead(), true);
        $this->assertSame($this->message2->isRead(), false);
    }
}
