<?php
declare(strict_types=1);

namespace App\Infraestructure\Database\Factory;

use App\Infraestructure\Database\DatabaseInterface;

interface DatabaseFactoryInterface
{
    public function getInstance() : DatabaseInterface;
}