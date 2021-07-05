<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJS\Tests\Unit;

use CarAndClassic\TalkJS\Api\MessageApi;
use CarAndClassic\TalkJS\Enumerations\MessageType;
use CarAndClassic\TalkJS\Models\Message;
use CarAndClassic\TalkJS\Models\MessageCreated;
use Symfony\Component\HttpClient\Response\MockResponse;

final class MessageTest extends TestCase
{
    private string $conversationId;

    private string $senderId;

    private array $messages;

    protected function setUp(): void
    {
        $this->conversationId = 'testConversationId';
        $this->senderId = 'testSenderId';
        $this->messages = [
            [
                'id' => 2, // At time of writing results are returned descending
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
                'id' => 1,
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

    public function testFindMessages(): void
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

        $messages = $api->findMessages($this->conversationId);
        $this->assertIsArray($messages);
        $this->assertCount(2, $messages);
        foreach ($messages as $message) {
            $this->assertInstanceOf(Message::class, $message);
        }
        $this->assertTrue($messages[0]->isUserMessage());
        $this->assertTrue($messages[1]->isSystemMessage());
    }

    public function testPostSystemMessage(): void
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

        $messageCreated = $api
            ->postSystemMessage($this->conversationId, 'Test System Message', ['test' => 'test']);
        $this->assertInstanceOf(MessageCreated::class, $messageCreated);
        $this->assertTrue($messageCreated->isSystemMessage());
    }

    public function testPostUserMessage(): void
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
        $messageCreated = $api
            ->postUserMessage($this->conversationId, $this->senderId, 'Test System Message', ['test' => 'test']);
        $this->assertInstanceOf(MessageCreated::class, $messageCreated);
        $this->assertTrue($messageCreated->isUserMessage());
    }
}
