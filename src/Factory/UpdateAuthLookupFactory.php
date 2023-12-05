<?php
declare(strict_types=1);

namespace Zestic\Authentication\Factory;

use Zestic\Authentication\Interactor\UpdateAuthLookup;
use Psr\Container\ContainerInterface;

final class UpdateAuthLookupFactory
{
    public function __construct(
        private $configName = 'users',
    ) { }

    public function __invoke(ContainerInterface $container): UpdateAuthLookup
    {
        $authAdapter = $container->get($this->configName . '.authAdapter');

        return new UpdateAuthLookup($authAdapter);
    }
}
