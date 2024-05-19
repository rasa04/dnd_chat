<?php

declare(strict_types=1);

namespace App\Queue\Exception;

use Exception;

final class QueueConnectionNameNotSetException extends Exception
{
    protected $message = 'Queue connection not set';
}
