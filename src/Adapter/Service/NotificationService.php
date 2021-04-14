<?php

namespace App\Adapter\Service;

use App\Domain\Account\Transaction\NotificationServiceInterface;
use GuzzleHttp\Client;

final class NotificationService implements NotificationServiceInterface
{
    private Client $client;
    private string $uri;
    private int $tries;

    public function __construct(Client $client, string $uri, int $tries = 3)
    {
        $this->client = $client;
        $this->uri = $uri;
        $this->tries = $tries;
    }

    public function notify(): bool
    {
        for ($try = 0; $try < $this->tries; $try++)
        {
            $requestUri = $this->client->request('GET', $this->uri);
            $payload = json_decode($requestUri->getBody());

            if($payload->message == "Enviado")
            {
                return true;
            }
        }
        //log de erro, monolog
    }
}