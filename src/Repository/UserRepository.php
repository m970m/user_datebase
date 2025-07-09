<?php
declare(strict_types=1);

namespace App\Repository;

use App\DTO\UserDTO;
use App\Entity\User;

class UserRepository
{
    private array $users = [];
    private array $data = [];
    private int $lastId = 0;

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
        }

        foreach ($this->data as $user)
        {
            $this->lastId = max($this->lastId, $user['id']);
            $this->users[$user['id']] = new User(
                $user['id'],
                $user['name'],
                $user['surname'],
                $user['email']
            );
        }
    }

    /**
     * @return User[]
     */
    public function getAllUsers(): array
    {
        return array_values($this->users);
    }

    public function addUser(UserDTO $userDTO): void
    {
        $id = ++$this->lastId;
        $user = new User($id, $userDTO->name, $userDTO->surname, $userDTO->email);
        $this->users[] = [$id => $user];
        $this->data[$id] = $user->toArray();

        $json = json_encode(array_values($this->data));
        if (file_put_contents($this->usersJsonPath, $json) === false)
        {
            unset($this->users[$id]);
            unset($this->data[$id]);
            $this->lastId--;
            throw new \RuntimeException("Error writing to file!");
        }
    }

    public function deleteUser(int $id): void
    {
        if (!isset($this->users[$id]))
        {
            throw new \RuntimeException("User with id: {$id} does not exist.");
        }

        unset($this->users[$id]);
        unset($this->data[$id]);

        $json = json_encode(array_values($this->data));
        if (file_put_contents($this->usersJsonPath, $json) === false)
        {
            throw new \RuntimeException("User deletion error.");
        }
    }
}