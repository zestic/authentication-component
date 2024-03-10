<?php

declare(strict_types=1);

namespace Zestic\Authentication;

enum AuthenticationResponseErrors: string
{
    case RESET_TOKEN_NOT_FOUND = 'RESET_TOKEN_NOT_FOUND';
    case SUCCESS = 'SUCCESS';
    case SYSTEM_ERROR = 'SYSTEM_ERROR';
}
