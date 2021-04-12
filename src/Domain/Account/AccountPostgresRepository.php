<?php

namespace App\Domain\Account;

use App\Infraestructure\Database\Factory\PostgresFactory;

class AccountPostgresRepository implements AccountRepositoryInterface
{
    private PostgresFactory $factory;

    public function __construct(
      PostgresFactory $factory
    ) {
        $this->factory = $factory;
    }
    public function find(string $id): Account
    {
        $instance = $this->factory->getInstance();
        $instance->select("SELECT * FROM account where id=$id");
    }

    public function push(Account $account)
    {
        $instance = $this->factory->getInstance();

        $attBalance = $account->getBalance();
        $accounId = $account->getId();
        $instance->select("UPDATE account set balance = $attBalance WHERE id=$accounId");
    }
}