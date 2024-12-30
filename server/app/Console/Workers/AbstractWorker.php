<?php

declare(strict_types=1);

namespace App\Console\Workers;

use App\Queue\Enum\QueuesEnum;
use App\Services\Queue\QueueInterface;
use Illuminate\Console\Command;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use Throwable;

abstract class AbstractWorker extends Command
{
    protected QueueInterface $queue;

    public function __construct(QueueInterface $queue)
    {
        parent::__construct();
        $this->queue = $queue;
    }

    public function handle(): void
    {
        try {
            $this->info('Waiting for messages. To exit press CTRL+C');
            $this->queue->consume($this->getQueueHandlers());
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

    abstract public function getQueueHandlers(): array;
}
