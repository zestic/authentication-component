<?php
declare(strict_types=1);

namespace Zestic\Authentication;

use Zestic\Authentication\Interface\AuthenticationResponseInterface;
use Zestic\Authentication\Interface\UserInterface;

final class AuthenticationResponse implements AuthenticationResponseInterface
{
    public function response(UserInterface $user, string $jwt, int $expiresAt): array
    {
        return [
            'expiresAt' => $expiresAt,
            'jwt'       => $jwt,
            'user'      => $user,
            'success'   => true,
        ];
    }
}
