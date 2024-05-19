<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class IncorrectMessageWorkloadException extends Exception
{
    protected $message = 'Incorrect message workload';
}
