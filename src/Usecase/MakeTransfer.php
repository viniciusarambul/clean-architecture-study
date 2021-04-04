<?php

namespace App\Usecase;

use App\Infraestructure\Repository\UserArrayRepository;
use App\Domain\User\User;
use App\Domain\User\Account;
use App\Domain\Transaction\Transaction;
use App\Domain\Exception\InsuficientBalance;
use App\Domain\Exception\UserNotAllowedMakeTransaction;
use App\Domain\Exception\UserPayerDontExist;
use App\Domain\Transaction\AntifraudServiceInterface;
use App\Domain\Exception\TransferNotAuthorized;

class MakeTransfer
{

    private UserArrayRepository $repository;
    private AntifraudServiceInterface $antifraud;

    public function __construct(
        UserArrayRepository $repository,
        AntifraudServiceInterface $antifraud
    ) {
        $this->repository = $repository;
        $this->antifraud = $antifraud;
    }

    public function __invoke(
        string $payee, 
        string $payer, 
        float $amount
    ) {
        if(!$this->antifraud->authorize()){
            throw new TransferNotAuthorized;
        }

        $payee = $this->repository->find($payee);
        $payer = $this->repository->find($payer);

        if($payer->getType() == User::USER_MERCHANT){
            throw new UserNotAllowedMakeTransaction;
        }

        if($payer->getAccount()->getBalance() < $amount){
            throw new InsuficientBalance;
        }

        $payer->getAccount()->addTransaction(Transaction::debit($amount));
        $payee->getAccount()->addTransaction(Transaction::credit($amount));
        
    }

}
