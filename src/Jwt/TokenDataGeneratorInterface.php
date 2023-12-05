<?php
declare(strict_types=1);

namespace Zestic\Authentication\Jwt;

use Zestic\Authentication\Interface\AuthLookupInterface;

interface TokenDataGeneratorInterface
{
    public function generate(AuthLookupInterface $authLookup): TokenData;
}
