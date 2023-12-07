<?php
declare(strict_types=1);

namespace Zestic\Authentication\Repository;

use AMB\Interactor\Db\BoolToSQL;
use Domain\Membership\Entity\Member;
use Domain\Membership\Entity\Membership;
use Laminas\Db\Adapter\Adapter as DbAdapter;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Zestic\Authentication\DbTableAuthAdapter;
use Zestic\Authentication\Entity\NewAuthLookup;
use Zestic\Authentication\Interactor\CreateAuthLookup;
use Zestic\Authentication\Interactor\UpdateAuthLookup;
use Zestic\Authentication\Interface\NewAuthLookupInterface;

class AuthLookupRepository
{
    private DbAdapter $dbAdapter;
    private string $tableName;

    public function __construct(
        private CreateAuthLookup $createAuthLookup,
        private DbTableAuthAdapter $authAdapter,
        private UpdateAuthLookup $updateAuthLookup,
    ) {
        $this->dbAdapter = $this->authAdapter->getDbAdapter();
        $this->tableName = $this->authAdapter->getTableName();
    }

    public function create(NewAuthLookupInterface $authLookup): UuidInterface
    {
        return $this->createAuthLookup->create($authLookup);
    }

    public function createForMember(Membership $membership, Member $member, ?string $password = null): UuidInterface
    {
        $password = $password ?? base_convert((string)rand(1, 100000), 10, 36);

        $authLookup = new NewAuthLookup(
            $member->getEmail(),
            $password,
            $membership->getPmb(),
        );
        $id = $this->create($authLookup);
        $data = [
            'is_primary' => $member->isPrimary() ? 1 : 0,
            'user_id'    => $membership->getId(),
            'username'   => $membership->getPmb(),
        ];
        $this->updateLookup($id, $data);

        return $id;
    }

    public function deleteLookup(UuidInterface|string $id): bool
    {
        $id = (string) $id;
        $sql = <<<SQL
DELETE FROM {$this->tableName}
WHERE id = '{$id}';
SQL;
        $statement = $this->dbAdapter->createStatement($sql);
        $result = $statement->execute();

        return $result->valid();
    }

    public function getAuthAdapter(): DbTableAuthAdapter
    {
        return $this->authAdapter;
    }

    public function getDbAdapter(): DbAdapter
    {
        return $this->dbAdapter;
    }

    public function getIdentityColumn(): string
    {
        return $this->authAdapter->getIdentityColumn();
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getMembershipLookupIds(Membership $membership): ?array
    {
        $sql = <<<SQL
SELECT id
    FROM {$this->tableName}
WHERE user_id = '{$membership->getId()}'
SQL;

        $statement = $this->dbAdapter->createStatement($sql);
        $result = $statement->execute();
        if (0 === $result->count()) {
            return null;
        }
        $ids = [];
        foreach ($result as $data) {
            $ids[] = Uuid::fromString($data['id']);
        }

        return $ids;
    }


    public function getLookupId(Member $member): ?UuidInterface
    {
        $isPrimary = (new BoolToSQL)($member->isPrimary());
        $sql = <<<SQL
SELECT id
    FROM {$this->tableName}
WHERE email = '{$member->getEmail()}'
    AND is_primary = {$isPrimary}
    AND user_id = '{$member->getMembershipId()}'
SQL;
        $statement = $this->dbAdapter->createStatement($sql);
        $result = $statement->execute();
        if (!$data = $result->current()) {
            return null;
        }

        return Uuid::fromString($data['id']);
    }

    public function updateLookup(UuidInterface $id, array $data): bool
    {
        if (isset($data['isPrimary'])) {
            $data['is_primary'] = (new BoolToSQL)($data['isPrimary']);
            unset($data['isPrimary']);
        }
        if (isset($data['is_primary'])) {
            $data['is_primary'] = (new BoolToSQL)($data['is_primary']);
        }

        return $this->updateAuthLookup->update($id, $data);
    }

    public function updateEmail(Member $member, string $email): bool
    {
        if ($id = $this->getLookupId($member)) {
            $this->updateLookup($id, ['email' => $email]);

            return true;
        }

        return false;
    }
}
