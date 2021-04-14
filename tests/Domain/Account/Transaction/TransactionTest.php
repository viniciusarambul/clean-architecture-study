<?php

namespace Tests\Domain\Account\Transaction;

use App\Domain\Account\Transaction\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    public function testConstructTransaction()
    {
        $transaction = new Transaction("C", 100.00);

        $this->assertEquals(
            "C",
            $transaction->getType()
        );
        $this->assertEquals(
            100.00,
            $transaction->getAmount()
        );
    }
}