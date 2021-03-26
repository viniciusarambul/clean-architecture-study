<?php

namespace App\Usecase;

use App\Infraestructure\Repository\UserArrayRepository;
use App\Domain\User\User;
use App\Domain\User\Account;
use App\Domain\Transaction\Transaction;
use App\Domain\Transaction\InsuficientBalance;
use App\Domain\Transaction\UserNotAllowedMakeTransaction;

class MakeTransfer
{

    private UserArrayRepository $repository;

    public function __construct(
        UserArrayRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function __invoke(
        string $payee, 
        string $payer, 
        float $amount
    ) {
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
