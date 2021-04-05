<?php 
namespace App\Infraestructure\Repository;

use App\Domain\User\UserRepository;
use App\Domain\User\User;
use App\Domain\Exception\UserPayerDontExist;
use PHPUnit\Framework\TestCase;

class UserArrayRepository implements UserRepository
{
    private array $users;

    public function __construct(
        array $users
    ) {
        $this->users = $users;
    }

    public function find(string $id) : User
    {   
        return $this->users[$id];
    }

}