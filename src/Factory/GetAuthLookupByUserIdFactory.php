<?php
declare(strict_types=1);

namespace Zestic\Authentication\Factory;

use Zestic\Authentication\Interactor\GetAuthLookupByUserId;
use Psr\Container\ContainerInterface;

final class GetAuthLookupByUserIdFactory
{
    public function __construct(
        private $configName = 'users',
    ) { }

    public function __invoke(ContainerInterface $container): GetAuthLookupByUserId
    {
        $authAdapter = $container->get($this->configName . '.authAdapter');

        return new GetAuthLookupByUserId($authAdapter);
    }
}
