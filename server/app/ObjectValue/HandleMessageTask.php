<?php

declare(strict_types=1);

namespace App\ObjectValue;

use App\Enum\MessageTypeEnum;
use App\Exceptions\IncorrectMessageWorkloadException;
use Carbon\Carbon;

final class HandleMessageTask implements TaskInterface
{
    public const string GAME_ID = 'g_id';
    public const string USER_ID = 'u_id';
    public const string BODY = 'body';
    public const string MESSAGE_TYPE = 'm_type';

    public string $toSendViaWS
    {
        get => json_encode([
            self::BODY => $this->body,
            'from' => $this->userID,
            'time' => Carbon::now()->toISOString(),
        ]);
    }

    public string $toWorkload
    {
        get => json_encode([
            self::BODY => $this->body,
            self::GAME_ID => $this->gameID,
            self::USER_ID => $this->userID,
        ]);
    }

    public function __construct(
        public readonly string $body,
        public readonly int $gameID,
        public readonly int $userID,
        public readonly string $messageType = MessageTypeEnum::MessageType->value
    ) {
    }

    /**
     * @throws IncorrectMessageWorkloadException
     */
    public static function fromWorkload(string $workload): TaskInterface
    {
        $message = json_decode($workload, true);
        if (!isset($message[self::BODY], $message[self::GAME_ID], $message[self::USER_ID])) {
            throw new IncorrectMessageWorkloadException;
        }

        return new self(
            (string)$message[self::BODY],
            (int)$message[self::GAME_ID],
            (int)$message[self::USER_ID],
            (string)($message[self::MESSAGE_TYPE] ?? MessageTypeEnum::MessageType->value)
        );
    }

    public static function create(
        string $body,
        int $gameID,
        int $userID,
        string $messageType = MessageTypeEnum::MessageType->value
    ): TaskInterface {
        return new self($body, $gameID, $userID, $messageType);
    }
}
