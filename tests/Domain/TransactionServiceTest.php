<?php 
namespace App\Usecase;

use PHPUnit\Framework\TestCase;
use App\Domain\Account\Account;
use App\Domain\User\User;
use App\Domain\Transaction\Transaction;
use App\Domain\Transaction\InsuficientBalance;
use App\Domain\Transaction\UserNotAllowedMakeTransaction;
use App\Infraestructure\Repository\UserRepository;

final class MakeTransferTest extends TestCase
{
            
    public function testExecuteWithNoBalance() 
    {

        $this->expectException(InsuficientBalance::class);
        
        $creditTransaction = new Transaction("credit", 50.00);
        $debitTransaction = new Transaction("debit", 100.00);
        $payeeAccount = new Account("uuid123","114", []);
        $payerAccount = new Account("uuid123","114", [$creditTransaction]);
        $amount = 100.00;
        $users = [
            "1" => new User("1","vinicius","12345","vinicius@hotmail.com","123",User::USER_MERCHANT, $payeeAccount),
            "2" => new User("2","vinicius","12345","vinicius@hotmail.com","123",User::USER_PERSON, $payerAccount),
        ];
        
        $repository = new \App\Infraestructure\Repository\UserArrayRepository($users);
        
        $uc = new \App\Usecase\MakeTransfer($repository);
        
        $uc("1","2",$amount);

    }

    public function testPayerMerchant()
    {

        $this->expectException(UserNotAllowedMakeTransaction::class);

        $creditTransaction = new Transaction("credit", 100.00);
        $debitTransaction = new Transaction("debit", 100.00);
        $payeeAccount = new Account("uuid123","114", []);
        $payerAccount = new Account("uuid123","114", [$creditTransaction]);
        $amount = 100.00;
        $users = [
            "1" => new User("1","vinicius","12345","vinicius@hotmail.com","123",User::USER_MERCHANT, $payeeAccount),
            "2" => new User("2","vinicius","12345","vinicius@hotmail.com","123",User::USER_MERCHANT, $payerAccount),
        ];
        
        $repository = new \App\Infraestructure\Repository\UserArrayRepository($users);
        
        $uc = new \App\Usecase\MakeTransfer($repository);
        
        $uc("1","2",$amount);
    }

    public function testExecute() 
    {
        
        $creditTransaction = new Transaction("credit", 100.00);
        $debitTransaction = new Transaction("debit", 100.00);
        $payeeAccount = new Account("uuid123","114", []);
        $payerAccount = new Account("uuid123","114", [$creditTransaction]);
        $amount = 100.00;
        $users = [
            "1" => new User("1","vinicius","12345","vinicius@hotmail.com","123",User::USER_MERCHANT, $payeeAccount),
            "2" => new User("2","vinicius","12345","vinicius@hotmail.com","123",User::USER_PERSON, $payerAccount),
        ];
        
        $repository = new \App\Infraestructure\Repository\UserArrayRepository($users);
        
        $uc = new \App\Usecase\MakeTransfer($repository);
        
        $uc("1","2",$amount);

        $this->assertEquals(100.00, $users[1]->getAccount()->getBalance());
        $this->assertEquals(0, $users[2]->getAccount()->getBalance());
        $this->assertEquals(1, count($users[1]->getAccount()->getTransaction())); 
        $this->assertEquals(2, count($users[2]->getAccount()->getTransaction()));    
    
    }
        
}