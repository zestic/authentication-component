<?php
declare(strict_types=1);

namespace Zestic\Authentication\Jwt\Factory;

use Zestic\Authentication\Jwt\Interactor\DecodeJwtToken;
use Zestic\Authentication\Jwt\JwtConfiguration;
use Psr\Container\ContainerInterface;

class DecodeJwtTokenFactory
{
    public function __invoke(ContainerInterface $container): DecodeJwtToken
    {
        $configuration = $container->get(JwtConfiguration::class);

        return new DecodeJwtToken($configuration);
    }
}
