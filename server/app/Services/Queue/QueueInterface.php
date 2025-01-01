<?php

declare(strict_types=1);

namespace App\Services\Queue;

use App\ObjectValue\TaskInterface;
use App\Queue\Enum\QueuesEnum;

interface QueueInterface
{
    public function publish(TaskInterface $task, QueuesEnum $queueName): void;

    /**
     * @var array $handlersQueue {
     *     value of @see QueuesEnum => callback
     * }
     */
    public function consume(array $queueHandlers): void;
}
