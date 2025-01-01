<?php

declare(strict_types=1);

namespace App\Services\Queue;

use App\ObjectValue\TaskInterface;
use App\Queue\Enum\QueuesEnum;
use App\Queue\Exception\QueueNameNotSetException;
use ErrorException;
use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

final class RabbitService implements QueueInterface
{
    public function __construct(
        protected AMQPChannel $channel
    ) {
    }

    public function publish(TaskInterface $task, QueuesEnum $queueName): void
    {
        $this->channel->queue_declare($queueName->value, auto_delete: false);
        $this->channel->basic_publish(
            new AMQPMessage($task->toWorkload),
            routing_key: $queueName->value
        );
    }

    /**
     * @inheritDoc
     * @throws ErrorException|QueueNameNotSetException
     */
    public function consume(array $queueHandlers): void
    {
        foreach ($queueHandlers as $queueName => $handler) {
            if (!is_string($queueName)) {
                throw new QueueNameNotSetException();
            }
            $this->channel->queue_declare(queue: $queueName, auto_delete: false);
            $this->channel->basic_consume(
                queue: $queueName,
                callback: $handler
            );
        }

        $this->channel->consume();
    }

    /**
     * @throws Exception
     */
    public function __destruct()
    {
        $this->channel->close();
    }
}
