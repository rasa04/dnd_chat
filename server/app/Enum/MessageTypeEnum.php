<?php

declare(strict_types=1);

namespace App\Enum;

enum MessageTypeEnum: string
{
    /** Usual messages from users */
    case MessageType = '0';
    /** Dice result messages */
    case DiceType = '1';

    public static function getValues(): array
    {
        return [
            MessageTypeEnum::MessageType->value,
            MessageTypeEnum::DiceType->value,
        ];
    }
}
