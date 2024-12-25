<?php
declare(strict_types=1);

namespace Zestic\Authentication;

use Zestic\Authentication\Cli\ResetPasswordCommand;
use Zestic\Authentication\Factory\AuthenticateUsernamePasswordFactory;
use Zestic\Authentication\Factory\CheckForRestrictedUsernameFactory;
use Zestic\Authentication\Factory\CreateAuthLookupFactory;
use Zestic\Authentication\Factory\DbTableAuthAdapterFactory;
use Zestic\Authentication\Factory\DoesUserExistFactory;
use Zestic\Authentication\Factory\GetAuthLookupByUserIdFactory;
use Zestic\Authentication\Factory\GetAuthLookupByUsernameFactory;
use Zestic\Authentication\Factory\RegisterUserFactory;
use Zestic\Authentication\Factory\UpdateAuthLookupFactory;
use Zestic\Authentication\Factory\UpdatePasswordByUsernameFactory;
use Zestic\Authentication\Interactor\RegisterUser;
use App\Domain\Factory\Command\AuthenticateUserHandlerFactory;
use App\Domain\Handler\Mutation\AuthenticateUserHandler;
use App\Jwt\Factory\CreateJwtTokenFactory;
use App\Jwt\Factory\DecodeJwtTokenFactory;
use App\Jwt\Factory\JwtConfigurationFactory;
use App\Jwt\Interactor\CreateJwtToken;
use App\Jwt\Interactor\DecodeJwtToken;
use App\Jwt\JwtConfiguration;
use Zestic\Authentication\Interface\AuthenticationResponseInterface;
use Zestic\Authentication\Interface\CreateUserInterface;
use Zestic\Authentication\Interface\FindUserByIdInterface;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'graphqlauth'  => $this->getDefaultAuthSettings(),
            'laminas-cli'  => $this->getLaminasCli(),
        ];
    }

    private function getDefaultAuthSettings(): array
    {
        return [
            'users' => [
                'class'     => [
                    'authenticationResponse' => AuthenticationResponseInterface::class,
                    'findUser'               => FindUserByIdInterface::class,
                ],
                'column'    => [
                    'credential' => 'password',
                    'identity'   => 'username',
                ],
                'lookupTableName' => 'auth_lookups',
                'resetTableName' => 'reset_tokens',
            ],
        ];
    }

    private function getDependencies(): array
    {
        return [
            'aliases'   => [
                AuthenticationResponseInterface::class => AuthenticationResponse::class,
                RegisterUser::class                    => 'users.registerUser',
                'users.createUser'                     => CreateUserInterface::class,
            ],
            'factories' => [
                'users.authAdapter'                => new DbTableAuthAdapterFactory(),
                'users.authentication'             => new AuthenticateUsernamePasswordFactory(),
                'users.checkForRestrictedUsername' => new CheckForRestrictedUsernameFactory(),
                'users.createAuthLookup'           => new CreateAuthLookupFactory(),
                'users.doesUserExist'              => new DoesUserExistFactory(),
                'users.getAuthLookupByUserId'      => new GetAuthLookupByUserIdFactory(),
                'users.getAuthLookupByUsername'    => new GetAuthLookupByUsernameFactory(),
                'users.registerUser'               => new RegisterUserFactory(),
                'users.updateAuthLookup'           => new UpdateAuthLookupFactory(),
                'users.updatePasswordByUsername'   => new UpdatePasswordByUsernameFactory(),
                AuthenticateUserHandler::class     => AuthenticateUserHandlerFactory::class,
                CreateJwtToken::class              => CreateJwtTokenFactory::class,
                DecodeJwtToken::class              => DecodeJwtTokenFactory::class,
                JwtConfiguration::class            => JwtConfigurationFactory::class,
            ],
        ];
    }

    private function getLaminasCli(): array
    {
        return [
            'commands' => [
                'auth:reset-password'        => ResetPasswordCommand::class,
            ],
        ];
    }
}
