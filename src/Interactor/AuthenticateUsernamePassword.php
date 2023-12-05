<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interactor;

use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter;
use App\Jwt\Interactor\CreateJwtToken;
use Zestic\Authentication\Interface\AuthenticationResponseInterface;
use Zestic\Authentication\Interface\FindUserByIdInterface;

final class AuthenticateUsernamePassword
{
    public function __construct(
        private CallbackCheckAdapter $authAdapter,
        private CreateJwtToken $createJwtToken,
        private FindUserByIdInterface $findUserById,
        private AuthenticationResponseInterface $authenticationResponse,
    ) { }

    public function authenticate(string $identity, string $credential): array
    {
        $this->authAdapter
            ->setIdentity($identity)
            ->setCredential($credential);

        if (!$authLookup = $this->authAdapter->authenticateUser()) {
            $result = $this->authAdapter->getResult();

            return [
                'messages'   => $result?->getMessages(),
                'reasonCode' => $result?->getCode(),
                'success'    => false,
            ];
        }

        [$jwt, $expiresAt] = $this->createJwtToken->handle($authLookup);
        $user = $this->findUserById->find($authLookup->getUserId());

        return $this->authenticationResponse->response($user, $jwt, $expiresAt);
    }
}
