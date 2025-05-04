<?php

declare(strict_types=1);

namespace App\Services;

use Random\RandomException;

final class DiceRollService
{
    /**
     * Парсит в тексте макросы вида "2d20", "d6", "3к8" и возвращает результат бросков
     *
     * @param string $body
     * @return array{macro:string,rolls:int[],sum:int}[]
     * @throws RandomException
     */
    public function parseAndRoll(string $body): array
    {
        // Нормализуем: русская 'к' → латинская 'd' и нижний регистр
        $normalized = str_replace(
            'к',
            'd',
            mb_strtolower($body, 'UTF-8')
        );

        // Собираем все макросы
        preg_match_all('/(\d*)d(\d+)/u', $normalized, $matches, PREG_SET_ORDER);

        $results = [];
        foreach ($matches as $m) {
            [, $countStr, $sidesStr] = $m;
            $count = (int) ($countStr ?: 1);
            $sides = (int) $sidesStr;

            $rolls = [];
            for ($i = 0; $i < $count; $i++) {
                $rolls[] = random_int(1, $sides);
            }

            $results[] = [
                'macro' => $m[0],
                'rolls' => $rolls,
                'sum' => array_sum($rolls),
            ];
        }

        return $results;
    }
}
