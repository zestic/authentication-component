<?php
declare(strict_types=1);

namespace Zestic\Authentication\Factory\Repository;

use Psr\Container\ContainerInterface;
use Zestic\Authentication\Repository\AuthLookupRepository;

class AuthLookupRepositoryFactory
{
    public function __construct(
        private $configName = 'users',
    ) { }

    public function __invoke(ContainerInterface $container): AuthLookupRepository
    {
        return new AuthLookupRepository(
            $container->get("{$configName}.createAuthLookup"),
            $container->get("{$configName}.authAdapter"),
            $container->get("{$configName}.updateAuthLookup"),
        );
    }
}
