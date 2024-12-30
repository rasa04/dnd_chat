<?php

declare(strict_types=1);

namespace App\Http\Resources\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait CommonResolvesTrait
{
    public static function makeResolvedByModel(Model $message): array
    {
        return self::make($message)->resolve();
    }

    public static function makeResolvedByCollection(Collection $collection): array
    {
        return self::collection($collection)->resolve();
    }
}
