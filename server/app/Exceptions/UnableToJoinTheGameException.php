<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class UnableToJoinTheGameException extends Exception
{
    /** string */
    protected $message = 'Unable to join the game exception';
    /** int */
    protected $code = 403;
}
