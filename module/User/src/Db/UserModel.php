<?php

declare(strict_types=1);

namespace User\Db;

final class UserModel
{
    public function __construct(
        private ?int $id = null,
        private ?string $userName = null,
        private ?string $email = null,
        private ?string $password = null
    ) {
    }
}