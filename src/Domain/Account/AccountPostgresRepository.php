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
        $instance->select(sprintf("SELECT * FROM account where id=%s",
            $id
            )
        );
    }

    public function push(Account $account)
    {
        $instance = $this->factory->getInstance();

        $instance->select(sprintf("UPDATE account set balance = %s WHERE id=%s",
            $account->getBalance(),
            $account->getId()
            )
        );
    }
}