<?php 
namespace App\Infraestructure\Repository;

use App\Domain\User\UserRepository;
use App\Domain\User\User;

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