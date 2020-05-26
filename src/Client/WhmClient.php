<?php

namespace Mehio\WhmUtil\IpReporter\Client;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

class WhmClient
{
    private $client;

    public function __construct()
    {
        $this->client = HttpClient::create(['headers' => [
            'Authorization' => sprintf('whm root:%s', $_ENV['WHM_TOKEN']),
        ],
        ]);
    }

    public function call(string $method, string $endpoint): ResponseInterface
    {
        return $this->client->request($method, $_ENV['WHM_ENDPOINT'] . $endpoint);
    }
}
