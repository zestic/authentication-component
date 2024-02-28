<?php

declare(strict_types=1);

namespace Zestic\Authentication;

use Mezzio\Authentication\UserInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\UuidInterface;
use Zestic\Authentication\Interface\AuthenticationResponseInterface;
use Zestic\Authentication\Interface\GenerateAuthenticationResponseInterface;
use Zestic\Authentication\Interface\NewAuthLookupInterface;
use Zestic\Authentication\Interface\FindUserByIdInterface;
use Zestic\Authentication\Interface\UserClientDataInterface;

class AuthenticationManager
{
    public function __construct(
        protected AuthenticationRepository $authenticationRepository,
        protected GenerateAuthenticationResponseInterface $generateAuthenticationResults,
        protected ?LoggerInterface $logger = null,
        protected ?UserClientDataInterface $userClientData = null,
    ) {
    }

    public function authenticate(string $credential, ?string $password = null): AuthenticationResponseInterface
    {
        $authLookup = $this->authenticationRepository->authenticate($credential, $password);
        if (!$authLookup) {
            $result = $this->authenticationRepository->authenticationResult();
            $errors = $result->getMessages();

            $this->logFailedAuthentication($credential, implode(',', $errors));

            return $this->generateAuthenticationResults->failed($credential, $errors);
        }

        $result = $this->generateAuthenticationResults->succeeded($authLookup);

        if ($result->isSuccess()) {
            $this->logSuccessfulAuthentication($credential);
        } else {
            $this->logFailedAuthentication($credential);
        }

        return $result;
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

    private function logFailedAuthentication(string $credential, string $reason = ''): void
    {

    }

    private function logSuccessfulAuthentication(string $credential): void
    {

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
