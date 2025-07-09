<?php
declare(strict_types=1);

namespace App\Repository;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Service\UserRepositoryInterface;
use PDO;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private PDO $connection) {}

    public function getAllUsers(): array
    {
        $result = [];
        $stmt = $this->connection->query('SELECT * FROM user');
        while ($row = $stmt->fetch())
        {
            $result[] = new User($row['id'], $row['name'], $row['surname'], $row['email']);
        }

        return $result;
    }

    public function addUser(UserDTO $userDTO): void
    {
        try {
            $sql = 'ISERT INTO user (name, surname, email) VALUES (:name, :surname, :email)';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':name', $userDTO->name);
            $stmt->bindValue(':surname', $userDTO->surname);
            $stmt->bindValue(':email', $userDTO->email);
            $stmt->execute();
        }
        catch (\PDOException) {
            throw new \RuntimeException("Error adding new user");
        }

    }

    public function deleteUser(int $id): void
    {
        try {
            $sql = 'DELETE FROM user WHERE id = :id';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
        }
        catch (\PDOException)
        {
            throw new \RuntimeException("Error deleting user");
        }
    }
}