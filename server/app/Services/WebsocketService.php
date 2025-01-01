<?php

declare(strict_types=1);

namespace App\Services;

use WebSocket\BadOpcodeException;
use WebSocket\Client;

final class WebsocketService
{
    public const string WEBSOCKET_URL_PATTERN = '%s://%s:%s/ws?group_id=%s';

    /**
     * @throws BadOpcodeException
     */
    public function sendMessageToGroup(int $groupId, string $message): void
    {
        $config = config('services.websocket');

        new Client(
            sprintf(
                self::WEBSOCKET_URL_PATTERN,
                $config['protocol'] ?? '',
                $config['host'] ?? '',
                $config['port'] ?? '',
                $groupId
            )
        )->send($message);
    }
}
