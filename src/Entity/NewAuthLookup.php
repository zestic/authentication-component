<?php
declare(strict_types=1);

namespace Zestic\Authentication\Entity;

use Zestic\Authentication\Interface\NewAuthLookupInterface;

final class NewAuthLookup implements NewAuthLookupInterface
{
    public function __construct(
        private string $email,
        private string $password,
        private string|int $userId,
        private ?string $username = null,
    ) {}

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUserId(): string|int
    {
        return $this->userId;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }
}
