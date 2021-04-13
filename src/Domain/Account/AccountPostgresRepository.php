<?php

namespace App\Domain\Account;

use App\Domain\Account\Transaction\Transaction;
use App\Infraestructure\Database\PostgresFactory;

class AccountPostgresRepository implements AccountRepositoryInterface
{
    private PostgresFactory $factory;
    private const FIND_ACCOUNT = "SELECT * FROM accounts where id = '%s'";
    private const FIND_TRANSACTIONS_BY_ACCOUNT_ID = "SELECT * FROM transactions where payer_id = '%s' OR payee_id = '%s'";
    PRIVATE const INSERT_TRANSACTION = "INSERT INTO transactions (id, payer_id, payee_id, amount) VALUES ('1234','%s','%s',%d)";

    public function __construct(
      PostgresFactory $factory
    ) {
        $this->factory = $factory;
    }
    public function find(string $id): Account
    {
        $connection = $this->factory->getConnection();
        try {
            $query = $connection->query(sprintf(self::FIND_ACCOUNT, $id));
            $result = $query->fetch(\PDO::FETCH_ASSOC);

            return new Account(
                $result['id'],
                $result['number'],
                $result['type'],
                $result['user_id'],
                $this->findTransactionByAccountId($id)
            );

        }catch (\PDOException $e){
            print_r($e->getMessage());
        exit;
        }
    }

    public function push(Account $account)
    {
        $this->pushAccount($account);

        foreach ($account->getTransaction() as $transaction) {
            $this->pushTransaction($transaction);
        }

    }
    private function pushAccount(Account $account)
    {
        $connection = $this->factory->getConnection();
        $accountId = $account->getId();
        $userId = $account->getUserId();
        $accountNumber = $account->getAccountNumber();
        $accountType = $account->getType();
        $stmt = $connection->prepare("insert into accounts (id, user_id, number, type)
                    values (:account_id, :user_id, :number, :type)
                    on conflict (id) do 
                    update set user_id = :user_id, number = :number, type = :type
                    where accounts.id = :account_id");
        $stmt->bindParam('account_id', $accountId, \PDO::PARAM_STR);
        $stmt->bindParam('user_id', $userId, \PDO::PARAM_STR);
        $stmt->bindParam('number', $accountNumber, \PDO::PARAM_INT);
        $stmt->bindParam('type', $accountType, \PDO::PARAM_STR);

        var_dump($stmt->execute());
    }

    private function pushTransaction(Transaction $transaction)
    {
        //insert de transaction, validando a id da transaction
        $connection = $this->factory->getConnection();
        $transactionPayer = $transaction->getPayer();
        $transactionPayee = $transaction->getPayee();
        $transactionAmount = $transaction->getAmount();

        $stmt = $connection->query(
            sprintf(self::INSERT_TRANSACTION,
            $transactionPayer,
            $transactionPayee,
            $transactionAmount
            )
        );

        var_dump($stmt->execute());
    }

    private function findTransactionByAccountId(string $id)
    {
        $transactions = [];
        $connection = $this->factory->getConnection();
        try {
            $query = $connection->query(sprintf(self::FIND_TRANSACTIONS_BY_ACCOUNT_ID, $id, $id));
            var_dump(sprintf(self::FIND_TRANSACTIONS_BY_ACCOUNT_ID, $id, $id));

            while ($transaction = $query->fetch(\PDO::FETCH_ASSOC)) {
                var_dump($transaction);
                $type = $transaction['payer_id'] == $id ? Transaction::TRANSACTION_DEBIT : Transaction::TRANSACTION_CREDIT;
                $transactions[] = new Transaction($type, $transaction['amount'], $transaction['payer_id'], $transaction['payee_id']);
            }

            return $transactions;
        }catch (\PDOException $e) {
            print_r($e->getMessage());
        }

    }
}