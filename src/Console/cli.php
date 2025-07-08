<?php
declare(strict_types=1);

use App\Controller\UserController;
use App\Repository\UserRepository;
use App\Service\UserService;

require __DIR__ . "/../../vendor/autoload.php";

const usersJsonPath = __DIR__ . "/../users.json";

function route(array $args, string $usersJsonPath): void
{
    if (count($args) == 1)
    {
        throw new InvalidArgumentException();
    }

    $userRepository = new UserRepository($usersJsonPath);
    $userService = new UserService($userRepository);
    $userController = new UserController($userService);

    if ($args[1] == 'get')
    {
        $userController->getAllUsers();
        return;
    }

    if ($args[1] == 'new')
    {
        $userController->addUser();
        return;
    }

    if ($args[1] == 'delete' && isset($args[2]) && filter_var(($args[2]), FILTER_VALIDATE_INT))
    {
        $userController->deleteUser((int) $args[2]);
        return;
    }

    throw new InvalidArgumentException();
}

try
{
    route($argv, usersJsonPath);
} catch (Exception $e)
{
    echo $e->getMessage();
}

