<?php

namespace Tests\Domain\Account;

use App\Domain\Account\Account;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    public function testConstructAccount()
    {
        $account = new Account("1", "113", "person", "1", [1]);

        $this->assertEquals(
            "1",
            $account->getId()
        );
        $this->assertEquals(
            "113",
            $account->getAccountNumber()
        );
        $this->assertEquals(
            "person",
            $account->getType()
        );
        $this->assertEquals(
            "1",
            $account->getUserId()
        );
        $this->assertEquals(
            [1],
            $account->getTransaction()
        );

    }
}