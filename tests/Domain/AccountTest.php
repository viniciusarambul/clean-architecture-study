<?php 
namespace App\Domain;
use PHPUnit\Framework\TestCase;

final class AccountTest extends TestCase{

    public function testConstructAccount(){

        $account = new Account("uuid123","114", [1]);

        $this->assertEquals(
            'uuid123',
            $account->getId()
        );
        $this->assertEquals(
            '114',
            $account->getAccountNumber()
        );
        $this->assertEquals(
            [1],
            $account->getTransaction()
        );
    }
}
