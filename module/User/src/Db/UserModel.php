<?php

declare(strict_types=1);

namespace User\Db;

final class UserModel implements EntityInterface
{
    public function __construct(
        private ?int $id = null,
        private ?string $userName = null,
        private ?string $email = null,
        private ?string $password = null
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}