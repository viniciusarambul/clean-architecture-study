<?php
namespace App\Domain\Account;

use App\Domain\Transaction\Transaction;

class Account 
{

    private string $id;
    private string $accountNumber;
    private array $transaction;

    public function __construct(string $id, string $accountNumber, array $transaction)
    {
        $this->id = $id;
        $this->accountNumber = $accountNumber;
        $this->transaction = $transaction;

    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getAccountNumber() : string
    {
        return $this->accountNumber;
    }

    public function getTransaction() : array
    {
       return $this->transaction;
    }

    public function getBalance() : float
    {
        $servidor = "db";

        $usuario = "root";

        $senha ="transaction";

        $conexao = pg_connect('host=db user=root password=password dbname=transaction');

        $balance = 0;

        foreach($this->transaction as $transaction){
            if($transaction->getType() == Transaction::TRANSACTION_CREDIT){
                $balance+=$transaction->getAmount();
                $sql = "UPDATE account set balance = $balance where id = $this->id";
                pg_query($conexao, $sql);
                continue;
            }
            $balance-=$transaction->getAmount();
            $sql = "UPDATE account set balance = $balance where id = $this->id";
            pg_query($conexao, $sql);
        }
        return $balance;
    }

    public function addTransaction(Transaction $transaction)
    {
        $this->transaction[] = $transaction;
    }
}