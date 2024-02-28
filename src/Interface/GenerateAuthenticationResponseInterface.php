<?php

declare(strict_types=1);

namespace Zestic\Authentication\Interface;

interface GenerateAuthenticationResponseInterface
{
    public function failed(string $credential, array $errors): AuthenticationResponseInterface;
    
    public function succeeded(AuthLookupInterface $authLookup): AuthenticationResponseInterface;
}
