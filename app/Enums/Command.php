<?php

namespace App\Enums;

use ValueError;

enum Command: string
{
    case weather = 'weather';

    public static function fromString(string $value): self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }

        throw new ValueError("Нет такого значения: $value");
    }
}
