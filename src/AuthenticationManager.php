<?php

declare(strict_types=1);

namespace Zestic\Authentication;

use Mezzio\Authentication\UserInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\UuidInterface;
use Zestic\Authentication\Interface\NewAuthLookupInterface;
use Zestic\Authentication\Interface\FindUserByIdInterface;
use Zestic\Authentication\Interface\UserClientDataInterface;

class AuthenticationManager
{
    public function __construct(
        protected AuthenticationRepository $authenticationRepository,
        protected FindUserByIdInterface $findUserById,
        protected ?LoggerInterface $logger = null,
        protected ?UserClientDataInterface $userClientData = null,
    ) {
    }

    public function authenticate(string $credential, ?string $password = null): ?UserInterface
    {
        if (!$authLookup = $this->authenticationRepository->authenticate($credential, $password)) {
            $result = $this->authenticationRepository->authenticationResult();
        }

        $user = $this->findUserById->find($authLookup->getUserId());
        if (!$user) {
            // throw
        }
        $data = [
            'credential' => $credential,
            'success' => true,
        ];

        $this->logIt('AuthenticateUser', $data);

        return $user;
    }

    public function logout()
    {

    }

    public function setPassword()
    {

    }

    public function register(NewAuthLookupInterface $newAuthLookup): UuidInterface
    {
        $id = $this->authenticationRepository->createLookup($newAuthLookup);

        $data = [
            'email' => $newAuthLookup->getEmail(),
            'userId' => $newAuthLookup->getUserId(),
            'username' => $newAuthLookup->getUsername(),
            'success' => true,
        ];
        $this->logIt('RegisterUser', $data);

        return $id;
    }

    private function logIt(string $message, array $data): void
    {
        if (!$this->logger) {
            return;
        }
        $context['data'] = $data;
        if ($this->userClientData) {
            $context['userClientData'] = $this->userClientData->getData();
        }

        $this->logger->info($message, $context);
    }
}
