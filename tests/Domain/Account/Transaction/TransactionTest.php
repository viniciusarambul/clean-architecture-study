<?php

namespace Tests\Domain\Account\Transaction;

use App\Domain\Account\Transaction\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    public function testConstructTransaction()
    {
        $transaction = new Transaction('278198d3-fa96-4833-abab-82f9e67f4712',"credit", 100.00, '123', '456');

        $this->assertEquals(
            "278198d3-fa96-4833-abab-82f9e67f4712",
            $transaction->getId()
        );

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