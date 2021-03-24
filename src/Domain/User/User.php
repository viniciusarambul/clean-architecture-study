<?php
namespace App\Domain\User;

use App\Domain\Account\Account;

class User 
{

    const USER_PERSON = 'person';
    const USER_MERCHANT = 'merchant';
    
    private string $id;
    private string $name;
    private string $document;
    private string $email;
    private string $password;
    private string $type;
    private Account $account;

    public function __construct(
        string $id, 
        string $name, 
        string $document,
        string $email, 
        string $password, 
        string $type, 
        Account $account
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->document = $document;
        $this->email = $email;
        $this->password = $password;
        $this->type = $type;
        $this->account = $account;
    }
    
    public function getId() : string
    {
        return $this->id;
    }
    
    public function getName() : string
    {
        return $this->name;
    }
    
    public function getDocument() : string
    {
        return $this->document;
    }
    
    public function getEmail() : string
    {
        return $this->email;
    }

    public function getPassword() : string
    {
        return $this->password;
    }

    public function getType() : string
    {
        return $this->type;
    }
    
    public function getAccount() : Account
    {
        return $this->account;
    }
    

}