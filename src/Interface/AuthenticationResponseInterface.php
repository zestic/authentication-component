<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interface;

use Zestic\Authentication\Interface\UserInterface;

interface AuthenticationResponseInterface
{
    public function response(UserInterface $user, string $jwt, int $expiresAt): array;
}
