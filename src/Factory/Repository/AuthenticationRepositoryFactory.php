<?php
declare(strict_types=1);

namespace Zestic\Authentication\Factory\Repository;

use Psr\Container\ContainerInterface;
use Zestic\Authentication\AuthenticationRepository;

class AuthenticationRepositoryFactory
{
    public function __construct(
        private $configName = 'users',
    ) { }

    public function __invoke(ContainerInterface $container): AuthenticationRepository
    {
        return new AuthenticationRepository(
            $container->get("{$this->configName}.authAdapter"),
        );
    }
}
