<?php

namespace App\Adapter\Service;

use App\Domain\Account\Transaction\AntifraudServiceInterface;
use GuzzleHttp\Client;

final class AntifraudService implements AntifraudServiceInterface
{
    private Client $client;
    private string $uri;

    public function __construct(Client $client, string $uri)
    {
        $this->client = $client;
        $this->uri = $uri;
    }

    public function authorize(): bool
    {
        $res = $this->client->request('GET', $this->uri);
        $payload = json_decode($res->getBody());

        return $payload->message == "Autorizado";
    }
}