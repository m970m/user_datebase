<?php
declare(strict_types=1);

namespace App\Repository\Factory;

use App\Database\ConnectionProvider;
use App\Database\DbSourceType;
use App\Env\Env;
use App\Repository\JsonUserRepository;
use App\Repository\UserRepository;
use App\Service\UserRepositoryInterface;

class RepositoryFactory
{
    public function __construct(private DbSourceType $dbSourceType, private array $config) {}

    public function createRepository(): UserRepositoryInterface
    {
        return match ($this->dbSourceType)
        {
            DbSourceType::JSON => new JsonUserRepository($this->config['jsonPath']),
            DbSourceType::MYSQL => new UserRepository(ConnectionProvider::getConnection($this->config))
        };
    }
}