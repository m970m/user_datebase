<?php
declare(strict_types=1);

use App\Database\ConnectionProvider;
use App\Repository\Factory\RepositoryFactory;
use App\Router\ConsoleRouter;
use App\Service\UserService;

require __DIR__ . "/../../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();

try
{
    $connectionProvider = new ConnectionProvider();
    $repositoryFactory = new RepositoryFactory($connectionProvider, $_ENV);
    $userRepository = $repositoryFactory->createRepository();
    $userService = new UserService($userRepository);
    $router = new ConsoleRouter($userService);
    $router->routeByArgs($argv);
} catch (Exception $e)
{
    echo $e->getMessage() . PHP_EOL;
}

