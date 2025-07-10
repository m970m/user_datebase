<?php
declare(strict_types=1);

namespace App\DTO;

readonly class UserDTO
{
    public function __construct(
        public string $name,
        public string $surname,
        public string $email
    ) {}
}