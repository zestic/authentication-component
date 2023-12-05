<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interactor;

use Zestic\Authentication\DbTableAuthAdapter;
use Zestic\Authentication\Interface\NewAuthLookupInterface;
use App\Exception\AuthLookupException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class CreateAuthLookup
{
    public function __construct(
        private DbTableAuthAdapter $authAdapter,
    ) {
    }

    public function create(NewAuthLookupInterface $newAuthLookup): UuidInterface
    {
        $username = $newAuthLookup->getUsername();
        $id = Uuid::uuid4();
        $password = password_hash($newAuthLookup->getPassword(), PASSWORD_BCRYPT);
        $email = strtolower($newAuthLookup->getEmail());
        $sql = <<<SQL
INSERT INTO {$this->authAdapter->getTableName()}
    (email, id, password, username)
     VALUES ('{$email}', '{$id->toString()}', '$password', '$username');
SQL;
        $dbAdapter = $this->authAdapter->getDbAdapter();
        $statement = $dbAdapter->createStatement($sql);
        $result    = $statement->execute();

        if ($result->valid()) {
            return $id;
        }

        throw new AuthLookupException('There was an problem saving the authentication user');
    }
}
