<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interface;

use Mezzio\Authentication\UserInterface as MezzioInterface;

interface AuthLookupInterface extends MezzioInterface
{
    public function getUserId();
}
