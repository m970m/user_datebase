<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Repository\UserRepository;

class UserService
{
    public function __construct(private UserRepository $userRepository) {}

    public function getAllUsers(): array
    {
        $users = $this->userRepository->getAllUsers();
        return array_map(fn($user) => $user->toArray(), $users);
    }

    public function addUser(): void
    {
        $user = new UserDTO(
            'Name' . rand(),
            'Surname' . rand(),
            'Email' . rand()
        );
        $this->userRepository->addUser($user);
    }

    public function deleteUser(int $id): void
    {
        $this->userRepository->deleteUser($id);
    }
}