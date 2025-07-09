<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\UserDTO;

class UserService
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function getAllUsers(): array
    {
        $users = $this->userRepository->getAllUsers();
        return array_map(fn($user) => $user->toArray(), $users);
    }

    public function addUser(): void
    {
        $user = new UserDTO(
            'Name' . rand(1, 10000),
            'Surname' . rand(1, 10000),
            'Email' . rand(1, 10000)
        );
        $this->userRepository->addUser($user);
    }

    public function deleteUser(int $id): void
    {
        $this->userRepository->deleteUser($id);
    }
}