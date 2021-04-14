<?php declare(strict_types=1);

include 'vendor/autoload.php';

use App\Usecase\MakeTransfer;
use App\Adapter\Service\AntifraudService;
use App\Adapter\Repository\AccountPostgresRepository;
use Slim\App;
use GuzzleHttp\Client;
use App\Infraestructure\Database\PostgresFactory;
use App\Adapter\Service\NotificationService;

$app = new App;
$app->post('/transaction', function ($req, $res, $args) {

    $factory = new PostgresFactory(
        'db',
        '5432',
        'transaction',
        'root',
        'password'
    );

    $client = new Client();
    $account = new AccountPostgresRepository($factory);
    $repository = new AntifraudService($client, 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
    $notify = new NotificationService($client, 'https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04');

    $c = new MakeTransfer($account, $repository, $notify);
    $payload = $req->getParsedBody();

    try {
        $c($payload['payee'],$payload['payer'],$payload['amount']);
    }catch (Exception $e)
    {
        print_r($e);
    }
    var_dump($req->getParsedBody());
    return $res->withHeader(
        'Content-Type',
        'application/json'
    );
});
$app->run();