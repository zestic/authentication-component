<?php
declare(strict_types=1);

namespace Zestic\Authentication\Jwt\Factory;

use Zestic\Authentication\Jwt\Interactor\CreateJwtToken;
use Zestic\Authentication\Jwt\JwtConfiguration;
use Zestic\Authentication\Jwt\TokenDataGeneratorInterface;
use Psr\Container\ContainerInterface;

class CreateJwtTokenFactory
{
    public function __invoke(ContainerInterface $container): CreateJwtToken
    {
        $configuration = $container->get(JwtConfiguration::class);
        $generator = $container->get(TokenDataGeneratorInterface::class);

        return new CreateJwtToken($configuration, $generator);
    }
}
