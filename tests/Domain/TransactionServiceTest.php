<?php 
namespace App\Domain;
use PHPUnit\Framework\TestCase;

final class TransactionServiceTest extends TestCase{

        
        public function testExecuteWithNoBalance() {

            $this->expectException(InsuficientBalance::class);
            

            $service = new TransactionService();
           
            $payeeAccount = new Account("uuid123","114", []);
            $payerAccount = new Account("uuid123","114", []);
            $payer = new User("1","vinicius","12345","vinicius@hotmail.com","123",User::USER_PERSON, $payerAccount);
            $payee = new User("1","vinicius","12345","vinicius@hotmail.com","123",User::USER_MERCHANT, $payeeAccount);
            $amout = 100.00;

            $service->makeTransfer($payer, $payee, $amout);

        }

        public function testPayerMerchant(){

            $this->expectException(UserNotAllowedMakeTransaction::class);

            $service = new TransactionService();
           
            $payeeAccount = new Account("uuid123","114", []);
            $payerAccount = new Account("uuid123","114", []);
            $payer = new User("1","vinicius","12345","vinicius@hotmail.com","123",User::USER_MERCHANT, $payerAccount);
            $payee = new User("1","vinicius","12345","vinicius@hotmail.com","123",User::USER_MERCHANT, $payeeAccount);
            $amout = 100.00;

            $service->makeTransfer($payer, $payee, $amout);
        }

        public function testExecute() {
            $service = new TransactionService();
            $creditTransaction = new Transaction("credit", 100.00);
            $debitTransaction = new Transaction("debit", 100.00);
            $payeeAccount = new Account("uuid123","114", []);
            $payerAccount = new Account("uuid123","114", [$creditTransaction]);
            $payer = new User("1","vinicius","12345","vinicius@hotmail.com","123",User::USER_PERSON, $payerAccount);
            $payee = new User("1","vinicius","12345","vinicius@hotmail.com","123",User::USER_MERCHANT, $payeeAccount);
            $amout = 100.00;

            $service->makeTransfer($payer, $payee, $amout);

            $this->assertEquals($payee->getAccount()->getBalance(), 100.00);
            $this->assertEquals($payer->getAccount()->getBalance(), 0);
            $this->assertEquals(count($payee->getAccount()->getTransaction()), 1); 
            $this->assertEquals(count($payer->getAccount()->getTransaction()), 2);    
        
        }
        
}