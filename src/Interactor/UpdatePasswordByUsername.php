<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interactor;

final class UpdatePasswordByUsername
{
    public function __construct(
        private GetAuthLookupByUsername $getAuthLookup,
        private UpdateAuthLookup $updateAuthLookup,
    ) { }

    public function update($username, $password)
    {
        $lookup = $this->getAuthLookup->get($username);
        $this->updateAuthLookup->update($lookup->getId(), ['password' => $password]);
    }
}
