<?php

namespace App\Domain;

class Account {

    private string $id;
    private string $accountNumber;
    private array $transaction;

    public function __construct(string $id, string $accountNumber, array $transaction){
        $this->id = $id;
        $this->accountNumber = $accountNumber;
        $this->transaction = $transaction;

    }

    public function getId() : string{
        return $this->id;
    }

    public function getAccountNumber() : string{
        return $this->accountNumber;
    }

    public function getTransaction() : array{
       return $this->transaction;
    }

    public function getBalance() : float{
        $balance = 0;

        foreach($this->transaction as $transaction){
            if($transaction->getType() == Transaction::TRANSACTION_CREDIT){
                $balance+=$transaction->getAmount();
                continue;
            }
            $balance-=$transaction->getAmount();
        }
        return $balance;
    }

    public function addTransaction(Transaction $transaction){
        $this->transaction[] = $transaction;
    }
}