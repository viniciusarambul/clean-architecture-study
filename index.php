<?php declare(strict_types=1);

include 'vendor/autoload.php';

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Domain\Transaction\Transaction;
use App\Domain\Account\Account;
use App\Domain\User\User;
use App\Infraestructure\Repository\UserRepository;
use App\Usecase\MakeTransfer;


$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$router = new League\Route\Router;


$builder = new DI\ContainerBuilder();
$builder->useAutowiring(false);

$builder->addDefinitions([
    UserRepository::class => function(\DI\Container $c){
        $creditTransaction = new Transaction("credit", 100.00);
        $debitTransaction = new Transaction("debit", 100.00);
        $payeeAccount = new Account("1","114", []);
        $payerAccount = new Account("3","114", [$creditTransaction]);
        $users = [
            "1" => new User("1","vinicius","12345","vinicius@hotmail.com","123",User::USER_MERCHANT, $payeeAccount),
            "2" => new User("3","vinicius","12345","vinicius@hotmail.com","123",User::USER_PERSON, $payerAccount),
        ];

        return  new \App\Infraestructure\Repository\UserArrayRepository($users);
    },
    AntifraudServiceInterface::class => function(\DI\Container $c){
        return  new \App\Infraestructure\Repository\Antifraud(true);
    },
    TransactionRepository::class => function(\DI\Container $c){
        return  new \App\Infraestructure\Repository\TransactionRepository($users[1], $amount, $users[2]);
    },
    MakeTransfer::class => function(\DI\Container $c){
        return new MakeTransfer($c->get(UserRepository::class), $c->get(AntifraudServiceInterface::class), $c->get(TransactionRepository::class));
    }
]);
$container = $builder->build();

// map a route
$router->map('POST', '/transaction', function (ServerRequestInterface $request) use($container): ResponseInterface {
    
    $body = json_decode(file_get_contents('php://input'));
   
    
    $uc = $container->get(MakeTransfer::class);
    
    $uc($body->payee,$body->payer,$body->amount);
    $response = new Laminas\Diactoros\Response;
    $response->getBody()->write('<h1>Hello, World!</h1>');
    $response = $response
        ->withHeader('Content-Type', 'application/json');
    return $response;
});

$response = $router->dispatch($request);

// send the response to the browser
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);