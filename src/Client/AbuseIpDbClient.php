<?php

namespace Mehio\WhmUtil\IpReporter\Client;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AbuseIpDbClient
{
    private $client;

    public function __construct()
    {
        $this->client = HttpClient::create(['headers' => [
            'Key' => $_ENV['ABUSEIPDB_KEY'],
        ],
        ]);
    }

    public function sendReport(string $payload): ResponseInterface
    {
        return $this->client->request('POST', 'https://api.abuseipdb.com/api/v2/report', ['body' => $payload]);
    }
}
