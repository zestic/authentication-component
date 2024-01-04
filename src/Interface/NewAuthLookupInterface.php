<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interface;

interface NewAuthLookupInterface
{
    public function getEmail(): string;
    public function getPassword(): string;
    public function getUserId(): string|int;
    public function getUsername(): ?string;
}
