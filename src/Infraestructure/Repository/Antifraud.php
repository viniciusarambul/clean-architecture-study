<?php
namespace App\Infraestructure\Repository;

use App\Domain\Transaction\AntifraudServiceInterface;

class Antifraud implements AntifraudServiceInterface
{
    private bool $authorized;

    public function __construct(bool $authorized){
        $this->authorized = $authorized;
    }

    public function authorize() : bool{
        return $this->authorized;
    }
}