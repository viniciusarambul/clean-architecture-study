<?php

namespace App\Usecase;

use App\Domain\Account\AccountRepositoryInterface;
use App\Domain\Account\Transaction\AntifraudServiceInterface;
use App\Domain\Account\Transaction\NotificationServiceInterface;
use App\Domain\Account\Transaction\Transaction;
use App\Usecase\Exception\InsuficientBalance;
use App\Usecase\Exception\TransferNotAuthorized;
use App\Usecase\Exception\UserNotAllowedMakeTransaction;

class MakeTransfer
{
    private AntifraudServiceInterface $antifraud;
    private AccountRepositoryInterface $accountRepository;
    private NotificationServiceInterface $notify;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        AntifraudServiceInterface $antifraud,
        NotificationServiceInterface $notify
    ) {
        $this->accountRepository = $accountRepository;
        $this->antifraud = $antifraud;
        $this->notify = $notify;
    }

    public function __invoke(
        string $payee, 
        string $payer, 
        float $amount
    ) {
        if(!$this->antifraud->authorize()){
            throw new TransferNotAuthorized();
        }

        $payee = $this->accountRepository->find($payee);
        $payer = $this->accountRepository->find($payer);

        if($payer->isMerchant()){
            throw new UserNotAllowedMakeTransaction();
        }

        if($payer->getBalance() < $amount){
            throw new InsuficientBalance();
        }

        var_dump($payer->getBalance());
        var_dump($payer->getTransaction());

        $payer->addTransaction(Transaction::debit($amount, $payer->getId(), $payee->getId()));

        $this->accountRepository->push($payee);
        $this->accountRepository->push($payer);

        $this->notify->notify();
    }

}
