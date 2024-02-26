<?php
declare(strict_types=1);

namespace Zestic\Authentication;

use Laminas\Authentication\Result;
use Laminas\Db\Adapter\Adapter as DbAdapter;
use Mezzio\Authentication\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Zestic\Authentication\DbTableAuthAdapter;
use Zestic\Authentication\Entity\AuthLookup;
use Zestic\Authentication\Entity\NewAuthLookup;
use Zestic\Authentication\Exception\AuthLookupException;
use Zestic\Authentication\Interactor\UpdateAuthLookup;
use Zestic\Authentication\Interface\AuthLookupInterface;
use Zestic\Authentication\Interface\NewAuthLookupInterface;

class AuthenticationRepository implements UserRepositoryInterface
{
    public DbAdapter $dbAdapter;
    public string $tableName;

    public function __construct(
        public readonly DbTableAuthAdapter $authAdapter,
    ) {
        $this->dbAdapter = $this->authAdapter->getDbAdapter();
        $this->tableName = $this->authAdapter->getTableName();
    }

    public function authenticate(string $identity, string $credential = null): ?AuthLookup
    {
        $this->authAdapter
            ->setIdentity($identity)
            ->setCredential($credential);

        return $this->authAdapter->authenticateUser();
    }

    public function authenticationResult(): ?Result
    {
        return $this->authAdapter->getResult();
    }

    public function createLookup(NewAuthLookupInterface $authLookup): UuidInterface
    {
        $username = $authLookup->getUsername();
        $id = Uuid::uuid4();
        $password = password_hash($authLookup->getPassword(), PASSWORD_BCRYPT);
        $email = strtolower($authLookup->getEmail());
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
WHERE id = '{$id}';
SQL;
        $statement = $this->dbAdapter->createStatement($sql);
        $result = $statement->execute();

        return $result->valid();
    }

    public function findLookupByEmail(string $email): ?AuthLookupInterface
    {
        return $this->authAdapter->findAuthLookupByParameter('email', $email);
    }

    public function findLookupByUserId(string|int $userId): ?AuthLookupInterface
    {
        return $this->authAdapter->findAuthLookupByParameter('user_id', $userId);
    }

    public function findLookupByUsername(string $username): ?AuthLookupInterface
    {
        return $this->authAdapter->findAuthLookupByParameter('username', $username);
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
        $setData = $this->prepDataForUpdate($data);
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
