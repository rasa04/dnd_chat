<?php

declare(strict_types=1);

namespace App\Repositories;

abstract class AbstractRepository implements RepositoryInterface
{
    public static function make(): AbstractRepository
    {
        return new static();
    }
}
