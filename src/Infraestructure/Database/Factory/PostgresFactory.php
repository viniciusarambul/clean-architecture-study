<?php
declare(strict_types=1);

namespace App\Infraestructure\Database\Factory;

use App\Infraestructure\Database\DatabaseInterface;
use App\Infraestructure\Database\Postgres;

class PostgresFactory implements DatabaseFactoryInterface
{
    private string $host;
    private string $port;
    private string $dbname;
    private string $user;
    private string $password;

    public function __construct(
        string $host,
        string $port,
        string $dbname,
        string $user,
        string $password
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
    }


    public function getInstance(): DatabaseInterface
    {
        return new Postgres(
            'db',
            '5432',
            'transaction',
            'root',
            'password'
        );
    }

}