<?php
declare(strict_types=1);

use App\Env\Env;
use App\Repository\Factory\RepositoryFactory;
use App\Router\ConsoleRouter;
use App\Service\UserService;

require __DIR__ . "/../../vendor/autoload.php";

try
{
    $config = require(__DIR__ . "/../../config/config.php");
    $env = new Env(__DIR__ . "/../../.env");
    $repositoryFactory = new RepositoryFactory($env->getDbSourceType(), $config);
    $userRepository = $repositoryFactory->createRepository();
    $userService = new UserService($userRepository);
    $router = new ConsoleRouter($userService);
    $router->routeByArgs($argv);
} catch (Exception $e)
{
    echo $e->getMessage() . PHP_EOL;
}

