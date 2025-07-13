<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;

interface UserRepositoryInterface
{
    /**
     * @return User[]
     */
    public function getAllUsers(): array;


    public function addUser(User $user): void;

    public function deleteUserById(int $userId): void;

    public function save(): void;
}