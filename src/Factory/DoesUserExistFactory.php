<?php
declare(strict_types=1);

namespace Zestic\Authentication\Factory;

use Zestic\Authentication\Interactor\DoesUserExist;
use Psr\Container\ContainerInterface;

final class DoesUserExistFactory
{
    public function __construct(
        private $configName = 'users',
    ) { }

    public function __invoke(ContainerInterface $container): DoesUserExist
    {
        $authAdapter = $container->get($this->configName . '.authAdapter');
        $checkForRestrictedUsername = $container->get($this->configName . '.checkForRestrictedUsername');

        return new DoesUserExist($checkForRestrictedUsername, $authAdapter);
    }
}
