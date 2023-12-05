<?php
declare(strict_types=1);

namespace Zestic\Authentication\Jwt\Interactor;

use Zestic\Authentication\Jwt\JwtConfiguration;
use Firebase\JWT\JWT;
use Zestic\Authentication\Interface\DecodeJwtTokenInterface;

class DecodeJwtToken implements DecodeJwtTokenInterface
{
    public function __construct(
        private JwtConfiguration $config,
    ) { }

    public function decode(string $jwt): array
    {
        $decoded = JWT::decode($jwt, $this->config->getPublicKey(), [$this->config->getAlgorithm()]);

        return (new StdClassToArray)($decoded);
    }
}
