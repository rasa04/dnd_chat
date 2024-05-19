<?php

declare(strict_types=1);

namespace App\Queue\Exception;

use Exception;

final class QueueNameNotSetException extends Exception
{
    protected $message = 'Queue name not set';
}
