<?php

declare(strict_types=1);

namespace App\ObjectValue;

interface TaskInterface
{
    public static function fromWorkload(string $workload): self;
}
