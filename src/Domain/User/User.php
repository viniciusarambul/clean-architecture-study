<?php
namespace App\Domain\User;

class User 
{
    private string $id;
    private string $name;
    private string $document;
    private string $email;
    private string $password;

    public function __construct(
        string $id, 
        string $name, 
        string $document,
        string $email, 
        string $password
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->document = $document;
        $this->email = $email;
        $this->password = $password;
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

}