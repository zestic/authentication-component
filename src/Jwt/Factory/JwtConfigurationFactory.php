<?php
declare(strict_types=1);

namespace Zestic\Authentication\Jwt\Factory;

use App\Exception\ConfigurationException;
use Zestic\Authentication\Jwt\JwtConfiguration;
use Exception;
use Psr\Container\ContainerInterface;

class JwtConfigurationFactory
{
    public function __invoke(ContainerInterface $container): JwtConfiguration
    {
        try {
            $config = $container->get('config');

            $jwtConfig = $this->hydrateConfig($config['jwt']);

            return new JwtConfiguration($jwtConfig);
        } catch (Exception $e) {
            throw new ConfigurationException('There was a problem with getting the configuration for jwt: ' . $e->getMessage());
        }
    }

    private function hydrateConfig(array $config): array
    {
        $privateKey = file_get_contents($config['privateKeyPath']);
        $publicKey = file_get_contents($config['publicKeyPath']);

        return [
            'algorithm' => $config['algorithm'],
            'privateKey' => $privateKey,
            'publicKey' => $publicKey,
            'tokenTtl' => $config['tokenTtl'],
        ];
    }
}
