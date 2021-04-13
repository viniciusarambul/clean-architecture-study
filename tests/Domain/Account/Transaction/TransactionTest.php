<?php

namespace Tests\Domain\Account\Transaction;

use App\Domain\Account\Transaction\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    public function testConstructTransaction()
    {
        $transaction = new Transaction("credit", 100.00, '123', '456');

        $this->assertEquals(
            "credit",
            $transaction->getType()
        );
        $this->assertEquals(
            100.00,
            $transaction->getAmount()
        );
        $this->assertEquals(
            "123",
            $transaction->getPayer()
        );
        $this->assertEquals(
            "456",
            $transaction->getPayee()
        );
    }
}