<?php
namespace App\Domain\Transaction;

interface AntifraudServiceInterface{
    public function authorize() : bool;
}