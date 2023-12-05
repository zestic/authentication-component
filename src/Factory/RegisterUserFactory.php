<?php
declare(strict_types=1);

namespace Zestic\Authentication\Factory;

use Zestic\Authentication\Interactor\RegisterUser;
use Psr\Container\ContainerInterface;

final class RegisterUserFactory
{
    public function __construct(
        private $configName = 'users',
    ) { }

    public function __invoke(ContainerInterface $container): RegisterUser
    {
        $createAuthLookup = $container->get($this->configName . '.createAuthLookup');
        $createUser = $container->get($this->configName . '.createUser');
        $updateAuthLookup = $container->get($this->configName . '.updateAuthLookup');

        return new RegisterUser(
            $createAuthLookup,
            $createUser,
            $updateAuthLookup,
        );
    }
}
