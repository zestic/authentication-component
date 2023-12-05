<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interactor;

use Zestic\Authentication\Interface\NewAuthLookupInterface;
use Zestic\Authentication\Interface\CreateUserInterface;

final class RegisterUser
{
    public function __construct(
        private CreateAuthLookup $createAuthLookup,
        private CreateUserInterface $createUser,
        private UpdateAuthLookup $updateAuthLookup,
    ) {
    }

    public function register(NewAuthLookupInterface $newAuthLookup): array
    {
        $lookupId = $this->createAuthLookup->create($newAuthLookup);
        $user = $this->createUser->create($newAuthLookup);
        $data = [
            'user_id' => $user->getId(),
        ];
        $this->updateAuthLookup->update($lookupId, $data);

        return [
            'user' => $user,
        ];
    }
}
