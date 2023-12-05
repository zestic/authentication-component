<?php
declare(strict_types=1);

namespace Zestic\Authentication\Factory;

use Zestic\Authentication\Interactor\UpdatePasswordByUsername;
use Psr\Container\ContainerInterface;

final class UpdatePasswordByUsernameFactory
{
    public function __construct(
        private $configName = 'users',
    ) { }

    public function __invoke(ContainerInterface $container): UpdatePasswordByUsername
    {
        $getAuthLookup = $container->get($this->configName . '.getAuthLookupByUsername');
        $updateAuthLookup = $container->get($this->configName . '.updateAuthLookup');

        return new UpdatePasswordByUsername($getAuthLookup, $updateAuthLookup);
    }
}
