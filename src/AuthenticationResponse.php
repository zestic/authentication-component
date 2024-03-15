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

    public function hasDetail(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public function getDetail(string $key): mixed
    {
        if (!isset($this->data[$key])) {
            throw new \Exception('The key "'. $key. '" does not exist. If you are not certain a key will exist, call the hasDetail() method first.');
        }

        return $this->data[$key];
    }
}
