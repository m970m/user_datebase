<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\UserService;

class UserController
{
    public function __construct(
        private UserService $userService
    ) {}

    public function listUsers(): array
    {
        return [
            'status' => 'success',
            'data' => $this->userService->getAllUsers()
        ];
    }

    public function deleteUser(int $id): array
    {
        $this->userService->deleteUser($id);

        return [
            'status' => 'success',
            'message' => "User deleted"
        ];
    }

    public function createUser(array $userData): array {
        $this->validateUserData($userData);
        $this->userService->addUser($userData);

        return [
            'status' => 'success',
            'message' => 'User created'
        ];
    }

    private function validateUserData(array $userData): void
    {
        if (!isset($userData['name'], $userData['surname'], $userData['email'])) {
            throw new \InvalidArgumentException('Incorrect user data');
        }
    }
}