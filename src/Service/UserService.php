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

    public function addUser(array $userData): array
    {
        $this->validateUserData($userData);
        $user = new User(
            name: $userData['name'],
            surname: $userData['surname'],
            email: $userData['email']
        );

        $this->userRepository->addUser($user);
        $this->userRepository->save();

        return $user->toArray();
    }

    public function deleteUser(int $id): void
    {
        $this->userRepository->deleteUserById($id);
        $this->userRepository->save();
    }

    private function validateUserData(array $userData): void
    {
        if (!isset($userData['name'], $userData['surname'], $userData['email']))
        {
            throw new \InvalidArgumentException('Incorrect user data');
        }
    }
}