<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interface;

use Mezzio\Authentication\UserInterface;

interface FindUserByIdInterface
{
    public function findById($id): ?UserInterface;
}
