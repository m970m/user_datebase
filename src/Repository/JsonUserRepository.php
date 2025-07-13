<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;

class JsonUserRepository implements UserRepositoryInterface
{
    private array $data = [];
    private int $lastId = 0;

    /**
     * @var User[]
     */
    private array $newUsers = [];
    private array $deletedUserIds = [];

    public function __construct(private string $usersJsonPath)
    {
        if (!file_exists($this->usersJsonPath))
        {
            throw new \RuntimeException("File {$this->usersJsonPath} does not exist");
        }

        $content = file_get_contents($this->usersJsonPath);
        if ($content === false)
        {
            throw new \RuntimeException("File {$this->usersJsonPath} reading error.");
        }

        $users = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE)
        {
            throw new \RuntimeException("Invalid JSON: " . json_last_error_msg());
        }

        foreach ($users as $user)
        {
            $this->data[$user['id']] = $user;
            $this->lastId = max($this->lastId, $user['id']);
        }
    }

    /**
     * @return User[]
     */
    public function getAllUsers(): array
    {
        return array_map(fn($user) => new User(
            name: $user['name'],
            surname: $user['surname'],
            email: $user['email'],
            id: $user['id'],
        ), $this->data);
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
        $backupData = $this->data;
        try {
            $this->deleteUsers();
            $this->insertUsers();
            $this->saveDataToFile();
            $this->deletedUserIds = [];
            $this->newUsers = [];
        } catch (\Throwable $e) {
            $this->data = $backupData;
            throw $e;
        }
    }

    private function insertUsers(): void
    {
        foreach ($this->newUsers as $user)
        {
            $id = ++$this->lastId;
            $user->setId($id);
            $this->data[$id] = $user->toArray();
        }
    }

    private function deleteUsers(): void
    {
        foreach ($this->deletedUserIds as $userId)
        {
            if (!isset($this->data[$userId]))
            {
                throw new \RuntimeException("User with id: {$userId} does not exist.");
            }

            unset($this->data[$userId]);
        }
    }

    private function saveDataToFile(): void
    {
        $json = json_encode(array_values($this->data));
        if (file_put_contents($this->usersJsonPath, $json) === false)
        {
            throw new \RuntimeException("Error writing to file!");
        }
    }
}