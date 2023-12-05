<?php
declare(strict_types=1);

namespace Zestic\Authentication\Jwt\Interactor;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Zestic\Authentication\Interface\AuthLookupInterface;
use Zestic\Authentication\Jwt\JwtConfiguration;
use Zestic\Authentication\Jwt\TokenDataGeneratorInterface;

class CreateJwtToken
{
    public function __construct(
        private JwtConfiguration $config,
        private TokenDataGeneratorInterface $tokenDataGenerator,
    ) { }

    public function handle(AuthLookupInterface $authLookup): array
    {
        $tokenData = $this->tokenDataGenerator->generate($authLookup);
        $data = $tokenData->getData();
        $expires = Carbon::now()->addSecond($this->config->getTokenTtl())->getTimestamp();
        $data['exp'] = $expires;

        return [
            JWT::encode($data, $this->config->getPrivateKey(), $this->config->getAlgorithm()),
            $expires,
        ];}
}
