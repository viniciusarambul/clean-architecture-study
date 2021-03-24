<?php
namespace App\Domain\Transaction;

use App\Domain\User\User;

class TransactionService 
{

    public function makeTransfer(User $payer,User $payee,float $amount)
    {

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