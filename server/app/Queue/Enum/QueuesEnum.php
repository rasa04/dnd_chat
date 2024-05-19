<?php

declare(strict_types=1);

namespace App\Queue\Enum;

enum QueuesEnum: string
{
    case HANDLE_MESSAGES = 'handle_messages';
}
