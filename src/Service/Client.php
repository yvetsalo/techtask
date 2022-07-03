<?php

namespace App\Service;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private ?GuzzleClient $client = null;

    public function get(string $uri, ?array $params = []): ResponseInterface
    {
        return $this->getClient()->get($uri, $params);
    }

    private function getClient()
    {
        if (null === $this->client) {
            $this->client = new GuzzleClient();
        }

        return $this->client;
    }
}
