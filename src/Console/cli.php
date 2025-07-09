<?php
declare(strict_types=1);

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

    if ($args[1] == 'get')
    {
        $users = $userService->getAllUsers();
        echo "Users:" . PHP_EOL;
        foreach ($users as $user)
        {
            echo "id: {$user['id']}, name: {$user['name']}, surname: {$user['surname']}, email: {$user['email']}";
        }
        return;
    }

    if ($args[1] == 'new')
    {
        $userService->addUser();
        echo 'A new user has been added.' . PHP_EOL;
        return;
    }

    if ($args[1] == 'delete' && isset($args[2]) && filter_var(($args[2]), FILTER_VALIDATE_INT))
    {
        $userService->deleteUser((int) $args[2]);
        echo "User with id {$args[2]} deleted." . PHP_EOL;
        return;
    }

    throw new InvalidArgumentException();
}

try
{
    route($argv, usersJsonPath);
} catch (Exception $e)
{
    echo $e->getMessage() . PHP_EOL;
}

