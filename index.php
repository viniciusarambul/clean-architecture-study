<?php declare(strict_types=1);

include 'vendor/autoload.php';

$client = new GuzzleHttp\Client();
$uri = "https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6";

$service = new \App\Adapter\Service\AntifraudService($client, $uri);

var_dump($service->authorize());