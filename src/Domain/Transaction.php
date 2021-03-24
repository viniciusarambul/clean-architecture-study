<?php

namespace App\Domain;

class Transaction{

    const TRANSACTION_CREDIT = 'credit';
    const TRANSACTION_DEBIT = 'debit';

    private string $type;
    private float $amount;

    public function __construct(string $type, float $amount){
        $this->type = $type;
        $this->amount = $amount;

    }

    public static function debit(float $amount) : Transaction{
        return new Transaction(Transaction::TRANSACTION_DEBIT, $amount);
    }
    
    public static function credit(float $amount) : Transaction{
        return new Transaction(Transaction::TRANSACTION_CREDIT, $amount);
    }

    public function getType() : string{
        return $this->type;
    }

    public function getAmount() : float{
        return $this->amount;
    }


}