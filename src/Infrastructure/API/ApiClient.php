<?php

namespace App\Infrastructure\API;


use GuzzleHttp\ClientInterface;

class ApiClient
{
    private ClientInterface $client;

    public function __construct(
        ClientInterface $client
    ){
        $this->client = $client;
    }

    public function getContent(string $url): string
    {
        $response = $this->client->request('GET', $url);
//        $resource = \GuzzleHttp\Psr7\Utils::tryFopen('/path/to/file', 'w');
//        $stream = \GuzzleHttp\Psr7\Utils::streamFor($resource);
//        $this->client->request('GET', $url, ['save_to' => $stream]);

        return (string) $response->getBody();
    }
}