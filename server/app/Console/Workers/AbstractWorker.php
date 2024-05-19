<?php

declare(strict_types=1);

namespace App\Console\Workers;

use App\Queue\Enum\QueuesEnum;
use App\Queue\Exception\QueueConnectionNameNotSetException;
use App\Queue\Exception\QueueNameNotSetException;
use App\Services\Queue\QueueInterface;
use Illuminate\Console\Command;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use Throwable;

abstract class AbstractWorker extends Command
{
    protected ?QueuesEnum $queueName = null;
    protected ?string $queueConnectionName = null;
    protected QueueInterface $queue;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws QueueConnectionNameNotSetException
     * @throws QueueNameNotSetException
     */
    public function handle(): void
    {
        if (is_null($this->queueConnectionName)) {
            throw new QueueConnectionNameNotSetException();
        }

        if (is_null($this->queueName)) {
            throw new QueueNameNotSetException();
        }

        try {
            $this->info('Waiting for messages. To exit press CTRL+C');
            $this->queue = new $this->queueConnectionName;

            $this->queue->consume([$this, 'process'], QueuesEnum::HANDLE_MESSAGES);
        } catch (AMQPTimeoutException $timeoutException) {
            $this->error(
                sprintf(
                    'Timeout error while consuming queue %s - %s',
                    QueuesEnum::HANDLE_MESSAGES->value,
                    $timeoutException->getMessage()
                ),
            );
        } catch (Throwable $exception) {
            $this->error(
                sprintf(
                    '%s | %s - %s',
                    QueuesEnum::HANDLE_MESSAGES->value,
                    $exception->getMessage(),
                    $exception->getTraceAsString()
                ),
            );
        }
    }

    abstract public function process($message): void;
}
