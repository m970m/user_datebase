<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\User;

interface UserRepositoryInterface
{
    /**
     * @return User[]
     */
    public function getAllUsers(): array;

    public function addUser(UserDTO $userDTO): void;

    public function deleteUser(int $id): void;
}