<?php
namespace App\Domain\Account;

use App\Domain\Account\Transaction\Transaction;

class Account {
    private string $id;
    private string $accountNumber;
    private string $type;
    private string $userId;
    private array $transaction;

    const ACCOUNT_PERSON = 'person';
    const ACCOUNT_MERCHANT = 'merchant';

    public function __construct(
        string $id,
        string $accountNumber,
        string $type,
        string $userId,
        array $transaction
    ) {
        $this->id = $id;
        $this->accountNumber = $accountNumber;
        $this->type = $type;
        $this->userId = $userId;
        $this->transaction = $transaction;

    }

    public function getBalance() : float
    {
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

    public function addTransaction(Transaction $transaction)
    {
        $this->transaction[] = $transaction;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return array
     */
    public function getTransaction(): array
    {
        return $this->transaction;
    }

    public function isMerchant(): bool
    {
        return $this->type == self::ACCOUNT_MERCHANT;
    }
}