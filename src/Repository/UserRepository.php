<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;

class UserRepository
{
    private array $users = [];
    private array $data = [];
    private int $lastId = 0;

    public function __construct(private string $usersJsonPath)
    {
        foreach (json_decode(file_get_contents($this->usersJsonPath), true) as $user)
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

    public function addUser(User $user): void
    {
        $this->users[] = [$user->getId() => $user];
        $this->data[$user->getId()] = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'email' => $user->getEmail()
        ];
        file_put_contents($this->usersJsonPath, json_encode(array_values($this->data)));
    }

    public function deleteUser(int $id): void
    {
        unset($this->users[$id]);
        unset($this->data[$id]);
        file_put_contents($this->usersJsonPath, json_encode(array_values($this->data)));
    }

    public function getNextId(): int
    {
        return ++$this->lastId;
    }
}