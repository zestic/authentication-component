<?php

declare(strict_types=1);

namespace Zestic\Authentication;

use Psr\Log\LoggerInterface;
use Ramsey\Uuid\UuidInterface;
use Zestic\Authentication\Interface\NewAuthLookupInterface;
use Zestic\Authentication\Interface\FindUserByIdInterface;
use Zestic\Authentication\Interface\UserClientDataInterface;

class AuthenticationManager
{
    public function __construct(
        private AuthLookupRepository $authLookupRepository,
        private FindUserByIdInterface $findUserById,
        private ?LoggerInterface $logger = null,
        private ?UserClientDataInterface $userClientData = null,
    ) {
    }

    public function authenticate()
    {

    }

    public function logout()
    {

    }

    public function setPassword()
    {

    }

    public function register(NewAuthLookupInterface $newAuthLookup): UuidInterface
    {
        $id = $this->authLookupRepository->createLookup($newAuthLookup);

        $data = [
            'email' => $newAuthLookup->getEmail(),
            'userId' => $newAuthLookup->getUserId(),
            'username' => $newAuthLookup->getUsername(),
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
