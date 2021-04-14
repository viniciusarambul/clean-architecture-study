<?php 
namespace App\Usecase;

use App\Adapter\Service\NotificationService;
use App\Domain\Account\Account;
use App\Domain\Account\AccountRepositoryInterface;
use App\Domain\Account\Transaction\AntifraudServiceInterface;
use App\Domain\Account\Transaction\NotificationServiceInterface;
use App\Domain\Account\Transaction\Transaction;
use App\Usecase\Exception\InsuficientBalance;
use App\Usecase\Exception\TransferNotAuthorized;
use App\Usecase\Exception\UserNotAllowedMakeTransaction;
use PHPUnit\Framework\TestCase;


final class MakeTransferTest extends TestCase
{
            
    public function testExecuteWithNoBalance() 
    {
        $this->expectException(InsuficientBalance::class);

        $payeeAccount = new Account("1","114", "test", "1", []);

        $antifraudMock = $this->createMock(AntifraudServiceInterface::class);

        $antifraudMock
            ->method('authorize')
            ->willReturn(true);

        $notifyMock = $this->createMock(NotificationServiceInterface::class);

        $accountRepostoryMock = $this->createMock(AccountRepositoryInterface::class);

        $accountRepostoryMock
            ->method("find")
            ->willReturn($payeeAccount);

        $accountRepostoryMock
            ->method("push")
            ->willReturn($payeeAccount);

        $uc = new MakeTransfer($accountRepostoryMock, $antifraudMock, $notifyMock);

        $uc("1","2", 100);
    }

    public function testMakeTransfer()
    {
        $payeeAccount = new Account("1","114", Account::ACCOUNT_PERSON, 1, []);
        $payerAccount = new Account("2","3",Account::ACCOUNT_PERSON, 2, [
            Transaction::credit(100.00, '2', '1')
        ]);

        $antifraudMock = $this->createMock(AntifraudServiceInterface::class);

        $antifraudMock
            ->method('authorize')
            ->willReturn(true);

        $notifyMock = $this->createMock(NotificationServiceInterface::class);

        $accountRepostoryMock = $this->createMock(AccountRepositoryInterface::class);

        $accountRepostoryMock
            ->method("find")
            ->will($this->onConsecutiveCalls($payeeAccount, $payerAccount));

        $accountRepostoryMock
            ->method("push")
            ->will($this->onConsecutiveCalls($payeeAccount, $payerAccount));


        $uc = new MakeTransfer($accountRepostoryMock, $antifraudMock, $notifyMock);

        $uc("1","2", 100);

        $this->assertEquals(0, $payerAccount->getBalance());
        $this->assertEquals(100, $payeeAccount->getBalance());
        $this->assertCount(2, $payerAccount->getTransaction());
        $this->assertCount(1,$payeeAccount->getTransaction());
        $this->assertEquals(Transaction::TRANSACTION_DEBIT, $payerAccount->getTransaction()[1]->getType());
        $this->assertEquals(Transaction::TRANSACTION_CREDIT, $payeeAccount->getTransaction()[0]->getType());
        $this->assertEquals($payerAccount->getId(), $payeeAccount->getTransaction()[0]->getPayee());
    }

    public function testPayerMerchant()
    {
        $this->expectException(UserNotAllowedMakeTransaction::class);

        $payeeAccount = new Account("1","114", Account::ACCOUNT_MERCHANT, 1, []);
        $payerAccount = new Account("2","3",Account::ACCOUNT_MERCHANT, 2, [
            Transaction::credit(100.00, '2', '1')
        ]);

        $antifraudMock = $this->createMock(AntifraudServiceInterface::class);

        $antifraudMock
            ->method('authorize')
            ->willReturn(true);

        $notifyMock = $this->createMock(NotificationServiceInterface::class);

        $accountRepostoryMock = $this->createMock(AccountRepositoryInterface::class);

        $accountRepostoryMock
            ->method("find")
            ->will($this->onConsecutiveCalls($payeeAccount, $payerAccount));

        $accountRepostoryMock
            ->method("push")
            ->will($this->onConsecutiveCalls($payeeAccount, $payerAccount));


        $uc = new MakeTransfer($accountRepostoryMock, $antifraudMock, $notifyMock);

        $uc("1","2", 100);

    }

    public function testNotAuthorized()
    {
        $this->expectException(TransferNotAuthorized::class);

        $payeeAccount = new Account("1","114", Account::ACCOUNT_MERCHANT, 1, []);
        $payerAccount = new Account("2","3",Account::ACCOUNT_MERCHANT, 2, [
            Transaction::credit(100.00, '2', '1')
        ]);

        $antifraudMock = $this->createMock(AntifraudServiceInterface::class);

        $antifraudMock
            ->method('authorize')
            ->willReturn(false);

        $notifyMock = $this->createMock(NotificationServiceInterface::class);

        $accountRepostoryMock = $this->createMock(AccountRepositoryInterface::class);

        $accountRepostoryMock
            ->method("find")
            ->will($this->onConsecutiveCalls($payeeAccount, $payerAccount));

        $accountRepostoryMock
            ->method("push")
            ->will($this->onConsecutiveCalls($payeeAccount, $payerAccount));

        $uc = new MakeTransfer($accountRepostoryMock, $antifraudMock, $notifyMock);

        $uc("1","2", 100);
    }
}

