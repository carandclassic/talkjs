<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Model\Conversation;

use CarAndClassic\TalkJS\Model\Collection;

class MessageCollection extends Collection
{
    /**
     * {@inheritdoc}
     */
    public static function createFromArray(array $data)
    {
        $messages = [];

        foreach ($data['data'] as $message) {
            $messages[$message['id']] = Message::createFromArray($message);
        }

        return new self($messages);
    }
}
