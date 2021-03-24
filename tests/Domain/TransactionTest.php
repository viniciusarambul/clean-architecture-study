<?php 
namespace App\Domain;
use PHPUnit\Framework\TestCase;

final class TransactionTest extends TestCase{

    public function testConstructTransaction(){

        //parametros = Beneficiário, Pagador e Valor
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