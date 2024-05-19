<?php

declare(strict_types=1);

namespace App\Services\Queue;

use App\Queue\Enum\QueuesEnum;
use ErrorException;
use Exception;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

final class RabbitService implements QueueInterface
{
    protected AMQPStreamConnection $connection;
    protected AbstractChannel $channel;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $queueConfig = config('queue.connections.rabbit');

        $this->connection = new AMQPStreamConnection(
            host: $queueConfig['host'],
            port: $queueConfig['port'],
            user: $queueConfig['user'],
            password: $queueConfig['password']
        );

        $this->channel = $this->connection->channel();
    }

    public function publish(array $data, QueuesEnum $queuesEnum): void
    {
        $this->channel->queue_declare(queue: $queuesEnum->value, auto_delete: false);
        $this->channel->basic_publish(new AMQPMessage(json_encode($data)), '', $queuesEnum->value);
    }

    /**
     * @throws ErrorException
     */
    public function consume(callable $processCallback, QueuesEnum $queuesEnum): void
    {
        $this->channel->queue_declare(queue: $queuesEnum->value, auto_delete: false);
        $this->channel->basic_consume(
            queue: $queuesEnum->value,
            callback: $processCallback
        );

        $this->channel->consume();
    }

    /**
     * @throws Exception
     */
    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
        logger('Connection closed.');
    }
}
