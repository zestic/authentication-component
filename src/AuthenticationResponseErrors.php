<?php

declare(strict_types=1);

namespace Zestic\Authentication;

enum AuthenticationResponseErrors: string
{
    case INVALID_RESET_TOKEN = 'INVALID_RESET_TOKEN';
    case SUCCESS = 'SUCCESS';
    case SYSTEM_ERROR = 'SYSTEM_ERROR';
}
