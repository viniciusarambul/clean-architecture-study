<?php 
namespace App\Infraestructure\Repository;

use App\Domain\Usecase\MakeTransfer;

class TransactionRepository
{

    public function save(int $payee, $amount,int $payer)
    {   

        $conexao = pg_connect("host=db user=root password=password dbname=transaction");

        $sql = "INSERT INTO transaction (payee,amount,payer) VALUES ($payee, $amount, $payer)";

        pg_query($conexao, $sql);
    }

}