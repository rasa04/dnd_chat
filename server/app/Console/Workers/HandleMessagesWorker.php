<?php

declare(strict_types=1);

namespace App\Console\Workers;

use App\Exceptions\IncorrectMessageWorkloadException;
use App\ObjectValue\HandleMessageTask;
use App\Queue\Enum\QueuesEnum;
use App\Services\Queue\RabbitService;
use App\Services\WebsocketService;
use PhpAmqpLib\Message\AMQPMessage;
use Throwable;
use WebSocket\BadOpcodeException;

final class HandleMessagesWorker extends AbstractWorker
{
    protected $signature = 'app:handle-messages-worker';
    protected $description = 'Command description';
    protected ?QueuesEnum $queueName = QueuesEnum::HANDLE_MESSAGES;
    protected ?string $queueConnectionName = RabbitService::class;

    /**
     * @throws BadOpcodeException
     * @throws Throwable
     * @throws IncorrectMessageWorkloadException
     */
    public function process($message): void
    {
        /** @var AMQPMessage $message */
        $this->info(sprintf('Handling message task - %s', $message->getBody()));
        $task = HandleMessageTask::fromWorkload($message->getBody());

        $this->info('Sending via web socket');
        WebsocketService::sendMessageToGroup($task->getGameID(), $task->toSendViaWS());

        $message->ack();
        $this->info('Messaged sent');
    }
}
