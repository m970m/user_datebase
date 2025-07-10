<?php
declare(strict_types=1);

namespace App\Database;

use PDO;

class ConnectionProvider
{
    public function getConnection(string $dsn, string $user, string $password): PDO
    {
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO($dsn, $user, $password, $opt);
    }
}