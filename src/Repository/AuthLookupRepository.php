<?php
declare(strict_types=1);

namespace Zestic\Authentication\Repository;

use Laminas\Db\Adapter\Adapter as DbAdapter;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Zestic\Authentication\DbTableAuthAdapter;
use Zestic\Authentication\Entity\NewAuthLookup;
use Zestic\Authentication\Exception\AuthLookupException;
use Zestic\Authentication\Interactor\UpdateAuthLookup;
use Zestic\Authentication\Interface\NewAuthLookupInterface;

class AuthLookupRepository implements UserRepositoryInterface
{
    public DbAdapter $dbAdapter;
    public string $tableName;

    public function __construct(
        public readonly DbTableAuthAdapter $authAdapter,
    ) {
        $this->dbAdapter = $this->authAdapter->getDbAdapter();
        $this->tableName = $this->authAdapter->getTableName();
    }

    public function create(NewAuthLookupInterface $authLookup): UuidInterface
    {
        $username = $newAuthLookup->getUsername();
        $id = Uuid::uuid4();
        $password = password_hash($newAuthLookup->getPassword(), PASSWORD_BCRYPT);
        $email = strtolower($newAuthLookup->getEmail());
        $sql = <<<SQL
INSERT INTO {$this->tableName}
    (email, id, password, username)
     VALUES ('{$email}', '{$id->toString()}', '$password', '$username');
SQL;
        $statement = $this->dbAdapter->createStatement($sql);
        $result = $statement->execute();
        if ($result->valid()) {
            return $id;
        }
        throw new AuthLookupException('There was an problem saving the authentication user');
    }

    public function deleteLookup(UuidInterface|string $id): bool
    {
        $id = (string)$id;
        $sql = <<<SQL
DELETE FROM {$this->tableName}
WHERE id =?
SQL;
        $statement = $this->dbAdapter->createStatement($sql);
        $result = $statement->execute();
    }

    public function findLookupByUserId($userId): ?AuthLookupInterface
    {
        return $this->authAdapter->findAuthLookupByParameter('user_id', $userId);
    }

    public function findLookupByUsername($userId): ?AuthLookupInterface
    {
        return $this->authAdapter->findAuthLookupByParameter('username', $userId);
    }

    public function isEmailAvailable(string $email): bool
    {
        $sql = <<<SQL
SELECT id 
FROM {$this->tableName}
WHERE LOWER(email) = LOWER('{$email}');
SQL;
        $statement = $this->dbAdapter->createStatement($sql);
        $result = $statement->execute();

        return !(bool)$result->getAffectedRows();
    }

    public function isUsernameAvailable(string $username): bool
    {
        if ($this->checkForRestrictedUsername->isRestricted($username)) {
            return false;
        }
        $sql = <<<SQL
SELECT id 
FROM {$this->tableName}
WHERE LOWER('{$this->authAdapter->getIdentityColumn()}') = LOWER('{$username}');
SQL;
        $statement = $this->dbAdapter->createStatement($sql);
        $result = $statement->execute();

        return !(bool)$result->getAffectedRows();
    }

    public function updateLookup(UuidInterface $id, array $data): bool
    {
        $setData = $this->prepData($data);
        $sql = <<<SQL
UPDATE {$this->authAdapter->getTableName()}
SET $setData
WHERE id = '{$id->toString()}';
SQL;
        $dbAdapter = $this->authAdapter->getDbAdapter();
        $statement = $dbAdapter->createStatement($sql);
        $result = $statement->execute();

        return $result->valid();
    }

    public function updatePasswordByUsername($username, $password)
    {
        $lookup = $this->findLookupByUsername($username);
        $this->updateLookup($lookup->getId(), ['password' => $password]);
    }

    private function prepDataForUpdate(array $data): string
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        unset($data['id']);
        $setData = [];
        foreach ($data as $column => $value) {
            $setData[] = "$column = '$value'";
        }

        return implode(',', $setData);
    }
}
