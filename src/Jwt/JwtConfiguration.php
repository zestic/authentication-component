<?php
declare(strict_types=1);

namespace Zestic\Authentication\Jwt;

final class JwtConfiguration
{
    /** @var string */
    private $algorithm;
    /** @var string */
    private $privateKey;
    /** @var string */
    private $publicKey;
    /** @var int */
    private $tokenTtl;

    public function __construct(array $config)
    {
        $this->algorithm = $config['algorithm'];
        $this->privateKey = $config['privateKey'];
        $this->publicKey = $config['publicKey'];
        $this->tokenTtl = $config['tokenTtl'];
    }

    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getTokenTtl(): int
    {
        return $this->tokenTtl;
    }
}
