<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interactor;

use Zestic\Authentication\DbTableAuthAdapter;
use Zestic\Authentication\Interface\AuthLookupInterface;

final class GetAuthLookupByUsername
{
    public function __construct(
        private DbTableAuthAdapter $authAdapter
    ) {
    }

    public function get($username): ?AuthLookupInterface
    {
        return $this->authAdapter->findAuthLookupByParameter('username', $username);
    }
}
