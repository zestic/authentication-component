<?php
declare(strict_types=1);

namespace Zestic\Authentication;

use Zestic\Authentication\Interface\AuthenticationResponseInterface;
use Zestic\Authentication\Interface\UserInterface;

final class AuthenticationResponse implements AuthenticationResponseInterface
{
    public function __construct(
        private array $data,
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->data['success'];
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
