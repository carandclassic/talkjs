<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Exceptions\Api;

use Symfony\Contracts\HttpClient\ResponseInterface;

final class UnknownErrorException extends \Exception
{
    protected $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $content = json_decode($response->getContent(false), true);

        if (!isset($content['reasons'])) {
            parent::__construct('Unknown error: No reason.');

            return;
        }

        $field = array_key_first($content['reasons']);
        $reasons = reset($content['reasons']);
        $reason = \is_array($reasons) ? $reasons[0] : $reasons;

        parent::__construct("Unknown error: Field $field: $reason.");
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
