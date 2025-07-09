<?php
declare(strict_types=1);

namespace App\Database;

use PDO;

class ConnectionProvider
{
    public static function getConnection($config): PDO {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['db']}";
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO($dsn, $config['user'], $config['password'], $opt);
    }
}