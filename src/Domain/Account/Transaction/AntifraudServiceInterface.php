<?php
namespace App\Domain\Account\Transaction;

interface AntifraudServiceInterface{
    public function authorize() : bool;
}