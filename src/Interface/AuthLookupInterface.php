<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interface;

use Mezzio\Authentication\UserInterface as MezzioInterface;
use Ramsey\Uuid\UuidInterface;

interface AuthLookupInterface extends MezzioInterface
{
    public function getId(): UuidInterface;
    public function getUserId();
    public function removeDetail(string $name);
    public function setDetail(string $name, $value);
}
