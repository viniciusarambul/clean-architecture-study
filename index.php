<?php declare(strict_types=1);

include 'vendor/autoload.php';

use App\Usecase\MakeTransfer;
use App\Adapter\Service\AntifraudService;
use App\Domain\Account\AccountPostgresRepository;
use Slim\App;
use GuzzleHttp\Client;
use App\Infraestructure\Database\PostgresFactory;

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

    $c = new MakeTransfer($account, $repository);
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