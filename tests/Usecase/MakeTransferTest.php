<?php 
namespace App\Usecase;

use App\Domain\Account\Account;
use App\Domain\Account\AccountRepositoryInterface;
use App\Domain\Account\Transaction\AntifraudServiceInterface;
use App\Domain\Account\Transaction\Transaction;
use App\Usecase\Exception\InsuficientBalance;
use PHPUnit\Framework\TestCase;


final class MakeTransferTest extends TestCase
{
            
    public function testExecuteWithNoBalance() 
    {
        $this->expectException(InsuficientBalance::class);

        $payeeAccount = new Account("1","114", "test", "test", []);

        $antifraudMock = $this->createMock(AntifraudServiceInterface::class);

        $antifraudMock
            ->method('authorize')
            ->willReturn(true);

        $accountRepostoryMock = $this->createMock(AccountRepositoryInterface::class);

        $accountRepostoryMock
            ->method("find")
            ->willReturn($payeeAccount);

        $uc = new MakeTransfer($accountRepostoryMock, $antifraudMock);

        $uc("1","2", 100);
    }
    public function testMakeTransfer()
    {
        $payeeAccount = new Account("1","114", Account::ACCOUNT_PERSON, 1, []);
        $payerAccount = new Account("2","115",Account::ACCOUNT_PERSON, 2, [
            Transaction::credit(100.00)
        ]);

        $antifraudMock = $this->createMock(AntifraudServiceInterface::class);

        $antifraudMock
            ->method('authorize')
            ->willReturn(true);

        $accountRepostoryMock = $this->createMock(AccountRepositoryInterface::class);

        $accountRepostoryMock
            ->method("find")
            ->will($this->onConsecutiveCalls($payeeAccount, $payerAccount));


        $uc = new MakeTransfer($accountRepostoryMock, $antifraudMock);

        $uc("1","2", 100);

        $this->assertEquals(0, $payerAccount->getBalance());
        $this->assertEquals(100, $payeeAccount->getBalance());
        $this->assertCount(2, $payerAccount->getTransaction());
        $this->assertCount(1,$payeeAccount->getTransaction());
    }
}

