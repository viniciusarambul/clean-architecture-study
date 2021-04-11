<?php

namespace App\Usecase;

use App\Domain\Account\AccountPostgresRepository;
use App\Domain\Account\Transaction\AntifraudServiceInterface;
use App\Domain\Account\Transaction\Transaction;
use App\Usecase\Exception\InsuficientBalance;
use App\Usecase\Exception\TransferNotAuthorized;
use App\Usecase\Exception\UserNotAllowedMakeTransaction;

class MakeTransfer
{

    private AntifraudServiceInterface $antifraud;
    private AccountPostgresRepository $accountRepository;

    public function __construct(
        AccountPostgresRepository $accountRepository,
        AntifraudServiceInterface $antifraud
    ) {
        $this->accountRepository = $accountRepository;
        $this->antifraud = $antifraud;
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

        $payer->addTransaction(Transaction::debit($amount));
        $payee->addTransaction(Transaction::credit($amount));

        $this->accountRepository->push($payee);
        $this->accountRepository->push($payer);
        
    }

}
