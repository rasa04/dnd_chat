<?php

declare(strict_types=1);

namespace App\Services;

use WebSocket\BadOpcodeException;
use WebSocket\Client;

final class WebsocketService
{
    public const WEBSOCKET_URL_PATTERN = 'ws://%s:%s/ws?group_id=%s';

    /**
     * @throws BadOpcodeException
     */
    public static function sendMessageToGroup(int $groupId, string $message): void
    {
        (
            new Client(
                sprintf(
                    self::WEBSOCKET_URL_PATTERN,
                    config('services.websocket.host'),
                    config('services.websocket.port'),
                    $groupId
                )
            )
        )->send($message);
    }
}
