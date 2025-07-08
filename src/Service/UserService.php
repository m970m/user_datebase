<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{
    public function __construct(private UserRepository $userRepository) {}

    public function getAllUsers(): string
    {
        $users = $this->userRepository->getAllUsers();
        $result = 'Users:' . PHP_EOL;
        foreach ($users as $user)
        {
            $result .= 'id = ' . $user->getId() .
                ', name = ' . $user->getName() .
                ', surname = ' . $user->getSurname() .
                ', email = ' . $user->getEmail() . PHP_EOL;
        }

        return $result;

    }

    public function addUser(): string
    {
        $id = $this->userRepository->getNextId();
        $user = new User(
            $id,
            'Name' . $id,
            'Surname' . $id,
            'Email' . $id
        );
        $this->userRepository->addUser($user);

        return 'User with id: ' . $id . ', name: ' . $user->getName() . ', surname: ' . $user->getSurname() .
            ', email: ' . $user->getEmail() . ' added.' . PHP_EOL;
    }

    public function deleteUser(int $id): string
    {
        $this->userRepository->deleteUser($id);
        return 'User with id: ' . $id . ' deleted.' . PHP_EOL;
    }
}