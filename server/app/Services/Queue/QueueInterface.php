<?php

declare(strict_types=1);

namespace App\Services\Queue;

use App\Queue\Enum\QueuesEnum;

interface QueueInterface
{
    public function publish(array $data, QueuesEnum $queuesEnum): void;
    public function consume(callable $processCallback, QueuesEnum $queuesEnum): void;
}
