<?php
declare(strict_types=1);

use App\Container\Container;
use App\Database\ConnectionProvider;
use App\Repository\Factory\RepositoryFactory;
use App\Repository\UserRepositoryInterface;
use App\Router\Router;

require __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$routes = require __DIR__ . "/../Config/routes.php";

try
{
    $container = new Container();
    $container->set(
        UserRepositoryInterface::class,
        fn() => new RepositoryFactory(new ConnectionProvider(), $_ENV)->createRepository()
    );
    $router = new Router($container, $routes);
    $postData = json_decode(file_get_contents('php://input'), true);
    $router->handleRequest($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'], $postData);
} catch (Exception $e)
{
    echo $e->getMessage() . PHP_EOL;
}