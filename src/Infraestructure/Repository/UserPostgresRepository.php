<?php 
namespace App\Infraestructure\Repository;

use App\Domain\User\UserRepository;
use App\Domain\User\User;
use App\Domain\Account\Account;

class UserPostgresRepository implements UserRepository
{

    public function find(string $id) : User
    {   
        $conexao = pg_connect("host=db user=root password=password dbname=transaction");

        $sql = "SELECT u.*, a.id as account_id, a.number as number, a.userid as user_id_account, a.balance as balance FROM users u left join account a on a.id = u.account where u.id = $id";

        $res = pg_query($conexao, $sql);
        $result = pg_fetch_assoc($res);
        
        //$account = new Account("$result[account_id]", "$result[number]", []);
        //return new User("$result[id]", "$result[name]", "$result[document]", "$result[email]", "$result[password]", "$result[type]", $account);
    }

}