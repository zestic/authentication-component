<?php

declare(strict_types=1);

namespace Zestic\Authentication\Entity;

class TableContext
{
    public function __construct(
        public string $authLookupTableName,
        public string $authLookupIdentityColumn,
        public string $authLookupCredentialColumn,
        public string $passwordResetTableName,
    ) {
    }
}
