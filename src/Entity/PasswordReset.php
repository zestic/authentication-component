<?php

declare(strict_types=1);

namespace Zestic\Authentication\Entity;

use Ramsey\Uuid\UuidInterface;

class PasswordReset
{
    private string $token;
    private UuidInterface $lookupId;

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): PasswordReset
    {
        $this->token = $token;

        return $this;
    }

    public function getLookupId(): UuidInterface
    {
        return $this->lookupId;
    }

    public function setLookupId(UuidInterface $lookupId): PasswordReset
    {
        $this->lookupId = $lookupId;

        return $this;
    }
}
