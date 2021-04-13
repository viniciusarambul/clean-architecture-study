<?php 
namespace App\Usecase;

use App\Domain\Account\Account;
use App\Domain\Account\AccountRepositoryInterface;
use App\Domain\Account\Transaction\AntifraudServiceInterface;
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

        $accountRepostoryMock = $this->createMock(AccountRepositoryInterface::class);

        $accountRepostoryMock
            ->method("find")
            ->willReturn($payeeAccount);

        $accountRepostoryMock
            ->method("push")
            ->willReturn($payeeAccount);

        $uc = new MakeTransfer($accountRepostoryMock, $antifraudMock);

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

        $accountRepostoryMock = $this->createMock(AccountRepositoryInterface::class);

        $accountRepostoryMock
            ->method("find")
            ->will($this->onConsecutiveCalls($payeeAccount, $payerAccount));

        $accountRepostoryMock
            ->method("push")
            ->will($this->onConsecutiveCalls($payeeAccount, $payerAccount));


        $uc = new MakeTransfer($accountRepostoryMock, $antifraudMock);

        $uc("1","2", 100);

        $this->assertEquals(0, $payerAccount->getBalance());
        $this->assertEquals(100, $payeeAccount->getBalance());
        $this->assertCount(2, $payerAccount->getTransaction());
        $this->assertCount(1,$payeeAccount->getTransaction());
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

        $accountRepostoryMock = $this->createMock(AccountRepositoryInterface::class);

        $accountRepostoryMock
            ->method("find")
            ->will($this->onConsecutiveCalls($payeeAccount, $payerAccount));

        $accountRepostoryMock
            ->method("push")
            ->will($this->onConsecutiveCalls($payeeAccount, $payerAccount));

        $uc = new MakeTransfer($accountRepostoryMock, $antifraudMock);

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

        $accountRepostoryMock = $this->createMock(AccountRepositoryInterface::class);

        $accountRepostoryMock
            ->method("find")
            ->will($this->onConsecutiveCalls($payeeAccount, $payerAccount));

        $accountRepostoryMock
            ->method("push")
            ->will($this->onConsecutiveCalls($payeeAccount, $payerAccount));

        $uc = new MakeTransfer($accountRepostoryMock, $antifraudMock);

        $uc("1","2", 100);
    }
}

