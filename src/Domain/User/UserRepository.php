<?php 
namespace App\Domain\User;

interface UserRepository
{
    public function find(string $id);
}