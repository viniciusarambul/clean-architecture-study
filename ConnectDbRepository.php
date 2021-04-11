<?php

require __DIR__ . '/vendor/autoload.php';

use App\Infraestructure\Database\Factory\PostgresFactory;

$postgresFactory = new PostgresFactory(
    'db',
    '5432',
    'transaction',
    'root',
    'password'
);
$postgresObject = $postgresFactory->createDb();

$users = $postgresObject->select('SELECT * FROM users');

var_dump($users);