<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interactor;

use Zestic\Authentication\DbTableAuthAdapter;

final class CheckForRestrictedUsername
{
    public function __construct(
        private DbTableAuthAdapter $authAdapter,
    ) {
    }

    public function isRestricted(string $username): bool
    {
        $sql = <<<SQL
SELECT id 
FROM restricted_usernames
WHERE LOWER(username) = LOWER('{$username}');
SQL;

        $dbAdapter = $this->authAdapter->getDbAdapter();
        $statement = $dbAdapter->createStatement($sql);
        $result    = $statement->execute();

        return (bool) $result->getAffectedRows();
    }
}
