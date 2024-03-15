<?php

declare(strict_types=1);

namespace Zestic\Authentication\Interface;

interface UserClientDataInterface
{
    public function getData(): array;
}
