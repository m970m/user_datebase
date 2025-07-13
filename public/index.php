<?php
declare(strict_types=1);

use App\Controller\UserController;
use App\Database\ConnectionProvider;
use App\Repository\Factory\RepositoryFactory;
use App\Router\Router;
use App\Service\UserService;

require __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

try
{
    $connectionProvider = new ConnectionProvider();
    $repositoryFactory = new RepositoryFactory($connectionProvider, $_ENV);
    $userRepository = $repositoryFactory->createRepository();
    $userService = new UserService($userRepository);
    $userController = new UserController($userService);
    $router = new Router($userController);
    $postData = json_decode(file_get_contents('php://input'), true);
    $router->handleRequest($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'], $postData);
} catch (Exception $e)
{
    echo $e->getMessage() . PHP_EOL;
}