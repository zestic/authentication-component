<?php
declare(strict_types=1);

namespace Zestic\Authentication\Factory;

use Zestic\Authentication\DbTableAuthAdapter;
use ConfigValue\GatherConfigValues;
use Laminas\Db\Adapter\Adapter;
use Psr\Container\ContainerInterface;

final class DbTableAuthAdapterFactory
{
    public function __construct(
        private $configName = 'users',
    ) { }

    public function __invoke(ContainerInterface $container): DbTableAuthAdapter
    {
        $authConfig = (new GatherConfigValues)($container, 'graphqlauth');
        $config = $authConfig[$this->configName];
        if (empty($config['credentialValidationCallback'])) {
            $config['credentialValidationCallback'] = function ($hash, $password) {
                return password_verify($password, $hash);
            };
        }
        $adapter = new Adapter(
            [
                'database' => $config['schema'],
                'driver'   => 'Pdo_Mysql',
                'hostname' => $config['host'],
                'password' => $config['password'],
                'port'     => $config['port'],
                'username' => $config['user'],
            ]
        );

        return new DbTableAuthAdapter(
            $adapter,
            $config['tableName'],
            $config['column']['identity'],
            $config['column']['credential'],
            $config['credentialValidationCallback'],
            $config['hasRestrictedUsernames'] ?? false,
        );
    }
}
