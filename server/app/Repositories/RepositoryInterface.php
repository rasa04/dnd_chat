<?php

declare(strict_types=1);

namespace App\Repositories;

interface RepositoryInterface
{
    /** @var int */
    public const MAX_LIMIT = 500;

    public static function make(): self;
}
