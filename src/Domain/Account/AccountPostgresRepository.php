<?php

namespace App\Domain\Account;

use App\Infraestructure\Database\Factory\PostgresFactory;

class AccountPostgresRepository implements AccountRepositoryInterface
{
    public function find(string $id): Account
    {
        $postgresFactory = new PostgresFactory(
            'db',
            '5432',
            'transaction',
            'root',
            'password'
        );

        $postgresObject = $postgresFactory->createDb();
        $accountId = $postgresObject->select("SELECT * FROM account where id=$id");

    }

    public function push(Account $account)
    {
        $postgresFactory = new PostgresFactory(
            'db',
            '5432',
            'transaction',
            'root',
            'password'
        );

        $postgresObject = $postgresFactory->createDb();

        $attBalance = $account->getBalance();
        $accounId = $account->getId();
        $balance = $postgresObject->select("UPDATE account set balance = $attBalance WHERE id=$accounId");


    }
}