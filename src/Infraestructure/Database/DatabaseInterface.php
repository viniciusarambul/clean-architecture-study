<?php

namespace App\Infraestructure\Database;

interface DatabaseInterface
{
    public function select(string $query) : array;
}