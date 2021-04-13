<?php
namespace App\Domain\Account\Transaction;

class Transaction
{

    const TRANSACTION_CREDIT = 'credit';
    const TRANSACTION_DEBIT = 'debit';

    private string $type;
    private float $amount;
    private string $payerId;
    private string $payeeId;

    public function __construct(
        string $type,
        float $amount,
        string $payerId,
        string $payeeId
    ) {
        $this->type = $type;
        $this->amount = $amount;
        $this->payerId = $payerId;
        $this->payeeId = $payeeId;

    }

    public static function debit(float $amount, string $payerId, string $payeeId) : Transaction
    {
        return new Transaction(Transaction::TRANSACTION_DEBIT, $amount, $payerId, $payeeId);
    }
    
    public static function credit(float $amount, string $payerId, string $payeeId) : Transaction
    {
        return new Transaction(Transaction::TRANSACTION_CREDIT, $amount, $payerId, $payeeId);
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
        return $this->payerId;
    }

    public function getPayee(): string
    {
        return $this->payeeId;
    }


}