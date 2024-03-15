<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interface;

interface AuthenticationResponseInterface
{
    public function getDetail(string $key): mixed;
    public function hasDetail(string $key): bool;
    public function isSuccess(): bool;
    public function toArray(): array;
}
