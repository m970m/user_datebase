<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use PDO;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User[]
     */
    private array $newUsers = [];
    private array $deletedUserIds = [];

    public function __construct(private PDO $connection) {}

    public function getAllUsers(): array
    {
        $result = [];
        $stmt = $this->connection->query('SELECT * FROM user');
        while ($row = $stmt->fetch())
        {
            $result[] = new User(
                name: $row['name'],
                surname: $row['surname'],
                email: $row['email'],
                id: $row['id'],
            );
        }

        return $result;
    }

    public function addUser(User $user): void
    {
        $this->newUsers[] = $user;
    }

    public function deleteUserById(int $userId): void
    {
        $this->deletedUserIds[] = $userId;
    }

    public function save(): void
    {
        $this->deleteUsers();
        $this->insertUsers();
        $this->deletedUserIds = [];
        $this->newUsers = [];

    }

    private function deleteUsers(): void
    {
        if (empty($this->deletedUserIds))
        {
            return;
        }

        $inConditionItems = [];
        foreach ($this->deletedUserIds as $key => $value)
        {
            $inConditionItems['user' . '_' . $key] = $value;
        }
        $inCondition = '(' . implode(',', array_map(fn($key) => ":$key", array_keys($inConditionItems))) . ')';

        try
        {
            $sql = "DELETE FROM user WHERE id IN $inCondition";
            $stmt = $this->connection->prepare($sql);
            foreach ($inConditionItems as $key => $value)
            {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
        } catch (\PDOException)
        {
            throw new \RuntimeException("Error deleting user");
        }
    }

    private function insertUsers(): void
    {
        if (empty($this->newUsers))
        {
            return;
        }

        [$placeholders, $data] = $this->prepareDataToInsert($this->newUsers, fn($user) => [
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'email' => $user->getEmail()
        ]);

        try
        {
            $sql = "INSERT INTO user (name, surname, email) VALUES $placeholders";
            $stmt = $this->connection->prepare($sql);
            foreach ($data as $key => $value)
            {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
        } catch (\PDOException)
        {
            throw new \RuntimeException("Error adding new user");
        }

    }

    private function prepareDataToInsert(array $users, callable $mapper): array
    {
        $placeholders = [];
        $data = [];
        foreach ($users as $index => $user)
        {
            $itemData = [];
            foreach ($mapper($user) as $key => $value)
            {
                $itemData[$key . '_' . $index] = $value;
            }
            $placeholders[] = '(' . implode(',', array_map(fn($key) => ":$key", array_keys($itemData))) . ')';
            foreach ($itemData as $key => $value)
            {
                $data[$key] = $value;
            }
        }

        return [implode(',', $placeholders), $data];
    }
}