<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;

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
        $user = new User(
            name: 'Name' . rand(1, 10000),
            surname: 'Surname' . rand(1, 10000),
            email: 'Email' . rand(1, 10000)
        );
        $this->userRepository->addUser($user);
        $this->userRepository->save();
    }

    public function deleteUser(int $id): void
    {
        $this->userRepository->deleteUserById($id);
        $this->userRepository->save();
    }
}