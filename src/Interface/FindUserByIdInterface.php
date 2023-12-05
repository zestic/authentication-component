<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interface;

interface FindUserByIdInterface
{
    public function find($id): ?UserInterface;
}
