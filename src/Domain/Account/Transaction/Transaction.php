<?php
namespace App\Domain\Account\Transaction;

class Transaction
{

    const TRANSACTION_CREDIT = 'credit';
    const TRANSACTION_DEBIT = 'debit';

    private string $type;
    private float $amount;
    private string $payer_id;
    private string $payee_id;

    public function __construct(string $type, float $amount, $payer_id, $payee_id)
    {
        $this->type = $type;
        $this->amount = $amount;
        $this->payer_id = $payer_id;
        $this->payee_id = $payee_id;

    }

    public static function debit(float $amount) : Transaction
    {
        return new Transaction(Transaction::TRANSACTION_DEBIT, $amount);
    }
    
    public static function credit(float $amount) : Transaction
    {
        return new Transaction(Transaction::TRANSACTION_CREDIT, $amount);
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getAmount() : float
    {
        return $this->amount;
    }

    public function getPayer(): string
    {
        return $this->payer_id;
    }

    public function getPayee(): string
    {
        return $this->payee_id;
    }


}