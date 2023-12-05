<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interface;

interface CreateUserInterface
{
    public function create($data = null): UserInterface;
}
