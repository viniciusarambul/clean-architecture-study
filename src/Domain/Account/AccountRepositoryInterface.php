<?php

namespace App\Domain\Account;

interface AccountRepositoryInterface
{
    public function find(string $id): Account;
    public function push(Account $account);
}