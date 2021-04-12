<?php
declare(strict_types=1);

namespace App\Infraestructure\Database;

use PDO;
use PDOException;

class Postgres
{
    private PDO $connection;

    public function __construct(
        string $host,
        string $port,
        string $dbname,
        string $user,
        string $password
    ) {
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s;user=%s;password=%s',
            $host,
            $port,
            $dbname,
            $user,
            $password
        );

        try {
            $this->connection = new PDO($dsn);
        }catch (PDOException $e) {
            echo $e->getMessage();
            die;
        }
    }

    public function select(string $query): array
    {
        $stmt = $this->connection->query($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}