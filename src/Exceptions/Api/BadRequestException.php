<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Exceptions\Api;

use Symfony\Contracts\HttpClient\ResponseInterface;

final class BadRequestException extends \Exception
{
    protected $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $content = json_decode($response->getContent(false), true);

        parent::__construct('Bad request. Content: ' . json_encode($content));
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
