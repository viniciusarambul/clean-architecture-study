<?php
namespace App\Domain\Account\Transaction;

use Ramsey\Uuid\Uuid;

class Transaction
{

    const TRANSACTION_CREDIT = 'credit';
    const TRANSACTION_DEBIT = 'debit';

    private string $id;
    private string $type;
    private float $amount;
    private string $payerId;
    private string $payeeId;

    public function __construct(
        string $id,
        string $type,
        float $amount,
        string $payerId,
        string $payeeId
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->amount = $amount;
        $this->payerId = $payerId;
        $this->payeeId = $payeeId;

    }

    public function getId(): string
    {
        return $this->id;
    }

    public static function debit(float $amount, string $payerId, string $payeeId) : Transaction
    {
        $id = Uuid::uuid4();
        return new Transaction($id->toString(),Transaction::TRANSACTION_DEBIT, $amount, $payerId, $payeeId);
    }

    public static function credit(float $amount, string $payerId, string $payeeId) : Transaction
    {
        $id = Uuid::uuid4();
        return new Transaction($id->toString(),Transaction::TRANSACTION_CREDIT, $amount, $payerId, $payeeId);
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