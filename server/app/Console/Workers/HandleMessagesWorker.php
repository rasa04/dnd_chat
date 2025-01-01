<?php

declare(strict_types=1);

namespace App\Console\Workers;

use App\Exceptions\IncorrectMessageWorkloadException;
use App\ObjectValue\HandleMessageTask;
use App\Queue\Enum\QueuesEnum;
use App\Services\WebsocketService;
use PhpAmqpLib\Message\AMQPMessage;
use Throwable;
use WebSocket\BadOpcodeException;

final class HandleMessagesWorker extends AbstractWorker
{
    /** @var string $signature */
    protected $signature = 'app:handle-messages-worker';
    /** @var string $description */
    protected $description = 'Command description';

    public function __construct(
        private readonly WebsocketService $websocketService
    ) {
        parent::__construct();
    }

    /**
     * @throws BadOpcodeException
     * @throws Throwable
     * @throws IncorrectMessageWorkloadException
     */
    public function handleMessages(AMQPMessage $message): void
    {
        $this->info(sprintf('Handling message task - %s', $message->getBody()));
        $task = HandleMessageTask::fromWorkload($message->getBody());

        $this->info('Sending via web socket');
        $this->websocketService->sendMessageToGroup($task->gameID, $task->toSendViaWS);

        $message->ack();
        $this->info('Messaged sent');
    }

    public function getQueueHandlers(): array
    {
        return [
            QueuesEnum::HANDLE_MESSAGES->value => [$this, 'handleMessages'],
        ];
    }
}
