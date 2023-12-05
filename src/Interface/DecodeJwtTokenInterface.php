<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interface;

interface DecodeJwtTokenInterface
{
    public function decode(string $jwt): array;
}
