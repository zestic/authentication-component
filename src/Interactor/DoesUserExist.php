<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interactor;

use Zestic\Authentication\DbTableAuthAdapter;

final class DoesUserExist
{
    public function __construct(
        private CheckForRestrictedUsername $checkForRestrictedUsername,
        private DbTableAuthAdapter $authAdapter,
    ) {
    }

    public function isEmailAvailable(string $email): bool
    {
        $sql = <<<SQL
SELECT id 
FROM {$this->authAdapter->getTableName()}
WHERE LOWER(email) = LOWER('{$email}');
SQL;

        $dbAdapter = $this->authAdapter->getDbAdapter();
        $statement = $dbAdapter->createStatement($sql);
        $result    = $statement->execute();

        return !(bool) $result->getAffectedRows();
    }

    public function isUsernameAvailable(string $username): bool
    {
        if ($this->checkForRestrictedUsername->isRestricted($username)) {
            return false;
        }

        $sql = <<<SQL
SELECT id 
FROM {$this->authAdapter->getTableName()}
WHERE LOWER('{$this->authAdapter->getIdentityColumn()}') = LOWER('{$username}');
SQL;

        $dbAdapter = $this->authAdapter->getDbAdapter();
        $statement = $dbAdapter->createStatement($sql);
        $result    = $statement->execute();

        return !(bool) $result->getAffectedRows();
    }
}
