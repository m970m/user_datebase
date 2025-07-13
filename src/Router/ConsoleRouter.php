<?php
declare(strict_types=1);

namespace App\Router;

use App\Service\UserService;

class ConsoleRouter
{
    private const HELP_MESSAGE = "use:\n'cli.php add' to add new user\n'cli.php get' to get all users\n'cli.php delete [id]' to delete user";

    public function __construct(
        private UserService $userService
    ) {}

    public function routeByArgs(array $args)
    {
        if (count($args) == 1)
        {
            throw new \InvalidArgumentException(self::HELP_MESSAGE);
        }

        if ($args[1] == 'get')
        {
            $users = $this->userService->getAllUsers();
            echo "Users:" . PHP_EOL;
            foreach ($users as $user)
            {
                echo "id: {$user['id']}, name: {$user['name']}, surname: {$user['surname']}, email: {$user['email']}" . PHP_EOL;
            }
            return;
        }

        if ($args[1] == 'add')
        {
            $this->userService->addUser();
            echo 'A new user has been added.' . PHP_EOL;
            return;
        }

        if ($args[1] == 'delete' && isset($args[2]) && filter_var(($args[2]), FILTER_VALIDATE_INT))
        {
            $this->userService->deleteUser((int) $args[2]);
            echo "User with id {$args[2]} deleted." . PHP_EOL;
            return;
        }

        throw new \InvalidArgumentException(self::HELP_MESSAGE);
    }
}