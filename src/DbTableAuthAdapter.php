<?php
declare(strict_types=1);

namespace Zestic\Authentication;

use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter;
use Laminas\Authentication\Result;
use Laminas\Db\Adapter\Adapter as DbAdapter;
use Laminas\Db\ResultSet\ResultSet;
use Zestic\Authentication\Entity\AuthLookup;

final class DbTableAuthAdapter extends CallbackCheckAdapter
{
    private ?Result $result;

    public function __construct(
        DbAdapter $laminasDb,
        $tableName,
        $identityColumn,
        $credentialColumn,
        $credentialValidationCallback,
        private $hasRestrictedUsernames,
    ) {
        parent::__construct(
            $laminasDb,
            $tableName,
            $identityColumn,
            $credentialColumn,
            $credentialValidationCallback,
        );
    }

    public function getDbAdapter(): DbAdapter
    {
        return $this->laminasDb;
    }

    public function hasRestrictedUsernames(): bool
    {
        return $this->hasRestrictedUsernames;
    }

    public function authenticateUser(): ?AuthLookup
    {
        $this->result = $this->authenticate();

        if (!$this->result || !$this->result->isValid()) {
            return null;
        }

        if (!$user = $this->getResultRowObject()) {
            return null;
        }

        $details = [
            'email'    => $user->email,
            'id'       => $user->id,
            'userId'   => $user->user_id,
            'username' => $user->username,
        ];

        return new AuthLookup($this->getIdentity(), [], $details);
    }

    public function getCredentialColumn(): ?string
    {
        return $this->credentialColumn;
    }

    public function getIdentityColumn(): string
    {
        return $this->identityColumn;
    }

    public function getResult(): ?Result
    {
        return $this->result;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function findAuthLookupByParameter(string $parameter, $value): ?AuthLookup
    {
        $sql = <<<SQL
SELECT *
FROM {$this->tableName}
WHERE `{$parameter}` = ?
SQL;
        $results = $this->laminasDb->query($sql, [$value], new ResultSet(ResultSet::TYPE_ARRAY));

        if (!$authLookup = $results->current()) {
            return null;
        }

        return new AuthLookup($authLookup['id'], [], $authLookup);
    }
}

