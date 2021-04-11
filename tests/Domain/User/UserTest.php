<?php

namespace Tests\Domain;

use App\Domain\User\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testCreateUser()
    {
        $user = new User("1", "vinicius", "102", "vinicius@hotmail.com","password");

        $this->assertEquals(
            "1",
            $user->getId()
        );
        $this->assertEquals(
            "vinicius",
            $user->getName()
        );
        $this->assertEquals(
            "102",
            $user->getDocument()
        );
        $this->assertEquals(
            "vinicius@hotmail.com",
            $user->getEmail()
        );
        $this->assertEquals(
            "password",
            $user->getPassword()
        );
    }
}