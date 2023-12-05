<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interactor;

use Zestic\Authentication\DbTableAuthAdapter;
use Ramsey\Uuid\UuidInterface;

final class UpdateAuthLookup
{
    public function __construct(
        private DbTableAuthAdapter $authAdapter
    ) {
    }

    public function update(UuidInterface $id, array $data): bool
    {
        $setData = $this->prepData($data);

        $sql = <<<SQL
UPDATE {$this->authAdapter->getTableName()}
SET $setData
WHERE id = '{$id->toString()}';
SQL;

        $dbAdapter = $this->authAdapter->getDbAdapter();
        $statement = $dbAdapter->createStatement($sql);
        $result    = $statement->execute();

        return $result->valid();
    }

    private function prepData(array $data): string
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
