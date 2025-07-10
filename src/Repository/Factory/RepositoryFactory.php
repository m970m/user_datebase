<?php
declare(strict_types=1);

namespace App\Repository\Factory;

use App\Database\ConnectionProvider;
use App\Repository\JsonUserRepository;
use App\Repository\UserRepository;
use App\Repository\UserRepositoryInterface;

class RepositoryFactory
{
    public function __construct(private ConnectionProvider $connectionProvider, private array $env) {}

    public function createRepository(): UserRepositoryInterface
    {
        return match ($this->env['DB_SOURCE'])
        {
            'json' => new JsonUserRepository($this->env['JSON_PATH']),
            'mysql' => new UserRepository(
                $this->connectionProvider->getConnection(
                    $this->env['DSN'],
                    $this->env['DB_USER'],
                    $this->env['DB_PASSWORD']
                )
            )
        };
    }
}