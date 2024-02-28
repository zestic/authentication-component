<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interface;

use Zestic\Authentication\Interface\UserInterface;

interface AuthenticationResponseInterface
{
    public function isSuccess(): bool;
    public function toArray(): array;
}
