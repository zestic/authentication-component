<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interactor;

use Zestic\Authentication\DbTableAuthAdapter;
use Zestic\Authentication\Interface\AuthLookupInterface;

final class GetAuthLookupByUserId
{
    public function __construct(
        private DbTableAuthAdapter $authAdapter
    ) {
    }

    public function get($userId): ?AuthLookupInterface
    {
        return $this->authAdapter->findAuthLookupByParameter('user_id', $userId);
    }
}
