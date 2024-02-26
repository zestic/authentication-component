<?php
declare(strict_types=1);

namespace Zestic\Authentication\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Zestic\Authentication\Interface\AuthLookupInterface;

class AuthLookup implements AuthLookupInterface
{
    public function __construct(
        protected string $identity,
        protected array $roles = [],
        protected array $details = [],
    ) {
    }

    public function getId(): UuidInterface
    {
        return Uuid::fromString($this->details['id']);
    }

    public function getUserId()
    {
        return $this->details['userId'];
    }

    public function getDetail(string $name, $default = null)
    {
        return $this->details[$name] ?? $default;
    }

    public function getIdentity() : string
    {
        return $this->identity;
    }

    public function getRoles() : array
    {
        return $this->roles;
    }

    public function getDetails() : array
    {
        return $this->details;
    }
}
