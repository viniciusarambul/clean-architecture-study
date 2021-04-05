<?php 
namespace App\Usecase;

use PHPUnit\Framework\TestCase;
use App\Domain\Account\Account;
use App\Domain\User\User;
use App\Domain\Transaction\Transaction;
use App\Domain\Exception\InsuficientBalance;
use App\Domain\Exception\UserNotAllowedMakeTransaction;
use App\Domain\Exception\UserPayerDontExist;
use App\Infraestructure\Repository\UserRepository;
use App\Domain\Transaction\AntifraudServiceInterface;
use App\Domain\Exception\TransferNotAuthorized;
use App\Domain\UseCase\MakeTransfer;
use App\Infraestructure\Repository\UserArrayRepository;

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
            "2" => new User("3","vinicius","12345","vinicius@hotmail.com","123",User::USER_PERSON, $payerAccount),
        ];
        
        $repository = new \App\Infraestructure\Repository\UserArrayRepository($users);
        $antifraud = new \App\Infraestructure\Repository\Antifraud(true);
        $transaction = new \App\Infraestructure\Repository\TransactionRepository($users[1], $amount, $users[2]);

        $uc = new \App\Usecase\MakeTransfer($repository, $antifraud, $transaction);
        
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
            "2" => new User("3","vinicius","12345","vinicius@hotmail.com","123",User::USER_MERCHANT, $payerAccount),
        ];
        
        $repository = new \App\Infraestructure\Repository\UserArrayRepository($users);
        $antifraud = new \App\Infraestructure\Repository\Antifraud(true);
        $transaction = new \App\Infraestructure\Repository\TransactionRepository($users[1], $amount, $users[2]);

        $uc = new \App\Usecase\MakeTransfer($repository, $antifraud, $transaction);
        
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
            "2" => new User("3","vinicius","12345","vinicius@hotmail.com","123",User::USER_PERSON, $payerAccount),
        ];
        
        $repository = new \App\Infraestructure\Repository\UserArrayRepository($users);
        $antifraud = new \App\Infraestructure\Repository\Antifraud(true);
        $transaction = new \App\Infraestructure\Repository\TransactionRepository($users[1], $amount, $users[2]);

        $uc = new \App\Usecase\MakeTransfer($repository, $antifraud, $transaction);
        
        $uc("1","2",$amount);

        $this->assertEquals(100.00, $users[1]->getAccount()->getBalance());
        $this->assertEquals(0, $users[2]->getAccount()->getBalance());
        $this->assertEquals(1, count($users[1]->getAccount()->getTransaction())); 
        $this->assertEquals(2, count($users[2]->getAccount()->getTransaction()));    
    
    }

    public function testNotAuthorized()
    {
        $this->expectException(TransferNotAuthorized::class);

        $creditTransaction = new Transaction("credit", 100.00);
        $debitTransaction = new Transaction("debit", 100.00);
        $payeeAccount = new Account("uuid123","114", []);
        $payerAccount = new Account("uuid123","114", [$creditTransaction]);
        $amount = 100.00;
        $users = [
            "1" => new User("1","vinicius","12345","vinicius@hotmail.com","123",User::USER_MERCHANT, $payeeAccount),
            "2" => new User("3","vinicius","12345","vinicius@hotmail.com","123",User::USER_PERSON, $payerAccount),
        ];
        
        $repository = new \App\Infraestructure\Repository\UserArrayRepository($users);
        $antifraud = new \App\Infraestructure\Repository\Antifraud(false);
        $transaction = new \App\Infraestructure\Repository\TransactionRepository($users[1], $amount, $users[2]);

        $uc = new \App\Usecase\MakeTransfer($repository, $antifraud, $transaction);
        
        $uc("1","2",$amount);


       
    }
        
}

