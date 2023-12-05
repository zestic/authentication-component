<?php
declare(strict_types=1);

namespace Zestic\Authentication\Factory;

use Zestic\Authentication\Interactor\StartPasswordReset;
use Psr\Container\ContainerInterface;

final class StartPasswordResetFactory
{
    public function __invoke(ContainerInterface $container): StartPasswordReset
    {
        $config = $container->get('config');
        $userConfig = $config['users'];
        $options = [
            'table' => $userConfig['table']
        ];
        $pdo = $container->get(\PDO::class);

        return new StartPasswordReset($pdo, $options);
    }
}
