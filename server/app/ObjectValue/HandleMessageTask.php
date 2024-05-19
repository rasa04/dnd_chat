<?php

declare(strict_types=1);

namespace App\ObjectValue;

use App\Exceptions\IncorrectMessageWorkloadException;
use Carbon\Carbon;

final class HandleMessageTask implements TaskInterface
{
    private string $body;
    private int $gameID;
    private int $userID;

    public function __construct(string $body, int $gameID, int $userID)
    {
        $this->body = $body;
        $this->gameID = $gameID;
        $this->userID = $userID;
    }

    /**
     * @throws IncorrectMessageWorkloadException
     */
    public static function fromWorkload(string $workload): TaskInterface
    {
        $workload = json_decode($workload, true);
        if (!isset($workload['message'])) {
            throw new IncorrectMessageWorkloadException;
        }
        $message = $workload['message'];

        if (!isset($message['body'], $message['game_id'], $message['user_id'])) {
            throw new IncorrectMessageWorkloadException;
        }

        return new self(
            (string)$message['body'],
            (int)$message['game_id'],
            (int)$message['user_id']
        );
    }

    public function toWorkload(): string
    {
        return json_encode([
            'body' => $this->getBody(),
            'game_id' => $this->getGameID(),
            'user_id' => $this->getUserID(),
        ]);
    }

    public function getUserID(): int
    {
        return $this->userID;
    }

    public function setUserID(int $userID): void
    {
        $this->userID = $userID;
    }

    public function getGameID(): int
    {
        return $this->gameID;
    }

    public function setGameID(int $gameID): void
    {
        $this->gameID = $gameID;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function toSendViaWS(): string
    {
        return json_encode([
            'body' => $this->getBody(),
            'from' => $this->getUserID(),
            'time' => Carbon::now()->toISOString(),
        ]);
    }
}
