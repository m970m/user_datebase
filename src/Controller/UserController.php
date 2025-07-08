<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\UserService;

class UserController
{
    public function __construct(private UserService $userService) {}

    public function getAllUsers(): void
    {
        echo $this->userService->getAllUsers();
    }

    public function addUser(): void
    {
        echo $this->userService->addUser();
    }

    public function deleteUser(int $id): void
    {
        echo $this->userService->deleteUser($id);
    }
}