<?php 
namespace App\Domain\User;

use PHPUnit\Framework\TestCase;
use App\Domain\Account\Account;

final class UserTest extends TestCase
{

    public function testConstructUser()
    {
        $account = new Account("1","123",[]);
        $user = new User("123","vinicius","12345","vinicius@hotmail.com","123",1, $account);

        $this->assertEquals(
            '123',
            $user->getId()
        );
        $this->assertEquals(
            'vinicius',
            $user->getName()
        );
        $this->assertEquals(
            '12345',
            $user->getDocument()
        );
        $this->assertEquals(
            'vinicius@hotmail.com',
            $user->getEmail()
        );
        $this->assertEquals(
            '123',
            $user->getPassword()
        );
        $this->assertEquals(
            1,
            $user->getType()
        );

    }

}