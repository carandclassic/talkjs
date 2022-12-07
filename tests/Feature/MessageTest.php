<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Tests\Feature;

use CarAndClassic\TalkJS\Api\MessageApi;
use CarAndClassic\TalkJS\Enumerations\MessageType;
use CarAndClassic\TalkJS\Events\MessageCreated;
use CarAndClassic\TalkJS\Events\MessageDeleted;
use CarAndClassic\TalkJS\Events\MessageEdited;
use CarAndClassic\TalkJS\Models\Message;
use CarAndClassic\TalkJS\Tests\TestCase;
use Symfony\Component\HttpClient\Response\MockResponse;

final class MessageTest extends TestCase
{
    private string $conversationId;

    private string $senderId;

    private array $messages;

    protected function setUp(): void
    {
        parent::setUp();
        $this->conversationId = 'testConversationId';
        $this->senderId = 'testSenderId';
        $this->messages = [
            [
                'id' => '2', // At time of writing results are returned descending
                'type' => MessageType::USER,
                'conversationId' => $this->conversationId,
                'senderId' => $this->senderId,
                'text' => 'Test User Message',
                'readBy' => [],
                'origin' => 'rest',
                'location' => null,
                'custom' => ['test' => 'test'],
                'createdAt' => (time() + 1) * 1000, // At time of writing TalkJS returns timestamp in milliseconds
                'attachment' => null
            ],
            [
                'id' => '1',
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
    }

    public function testGet(): void
    {
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode(['data' => $this->messages]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            MessageApi::class
        );

        $messages = $api->get($this->conversationId);
        $this->assertIsArray($messages);
        $this->assertCount(2, $messages);
        foreach ($messages as $message) {
            $this->assertInstanceOf(Message::class, $message);
        }
        $this->assertTrue($messages[$this->messages[0]['id']]->isUserMessage());
        $this->assertTrue($messages[$this->messages[1]['id']]->isSystemMessage());
    }

    public function testFind(): void
    {
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode($this->messages[0]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            MessageApi::class
        );

        $message = $api->find($this->conversationId, $this->messages[0]['id']);

        $this->assertInstanceOf(Message::class, $message);
        foreach ($this->messages[0] as $key => $value)
        {
            $this->assertEquals($value, $message->$key);
        }
    }

    public function testCreateSystemMessage(): void
    {
        $text = 'Test System Message';
        $custom = ['test' => 'test'];
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode(['data' => []]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            MessageApi::class
        );

        $messageCreated = $api
            ->createSystemMessage($this->conversationId, $text, $custom);

        $this->assertInstanceOf(MessageCreated::class, $messageCreated);
        $this->assertTrue($messageCreated->isSystemMessage());
        $this->assertEquals(null, $messageCreated->senderId);
        $this->assertEquals($text, $messageCreated->text);
        $this->assertEquals($custom, $messageCreated->custom);

    }

    public function testCreateUserMessage(): void
    {
        $text = 'Test User Message';
        $custom = ['test' => 'test'];
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode(['data' => []]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            MessageApi::class
        );

        $messageCreated = $api
            ->createUserMessage($this->conversationId, $this->senderId, $text, $custom);

        $this->assertInstanceOf(MessageCreated::class, $messageCreated);
        $this->assertTrue($messageCreated->isUserMessage());
        $this->assertEquals($this->senderId, $messageCreated->senderId);
        $this->assertEquals($text, $messageCreated->text);
        $this->assertEquals($custom, $messageCreated->custom);
    }

    public function testEdit(): void
    {
        $text = 'Test User Message';
        $custom = ['test' => 'test'];
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode(['data' => []]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            MessageApi::class
        );

        $messageEdited = $api->edit($this->conversationId, $this->messages[0]['id'], $text, $custom);

        $this->assertInstanceOf(MessageEdited::class, $messageEdited);
        $this->assertEquals($this->conversationId, $messageEdited->conversationId);
        $this->assertEquals($this->messages[0]['id'], $messageEdited->messageId);
        $this->assertEquals($text, $messageEdited->text);
        $this->assertEquals($custom, $messageEdited->custom);
    }

    //TODO: testSendFile

    public function testDelete(): void
    {
        $api = $this->createApiWithMockHttpClient(
            [
                new MockResponse(
                    json_encode(['data' => []]),
                    ['response_headers' => $this->defaultMockResponseHeaders]
                )
            ],
            MessageApi::class
        );

        $messageDeleted = $api->delete($this->conversationId, $this->messages[0]['id']);

        $this->assertInstanceOf(MessageDeleted::class, $messageDeleted);
    }
}
