<?php

namespace App\Enums;

enum BotName: string
{
    case botInfocur = 'botInfocur';
    case botMo = 'botMo';
    case botOrion = 'botOrion';
    case test = 'test';

    public function label(): string
    {
        return match ($this) {
            self::test => 'Тестовый',
            self::botInfocur => 'Терминал - инфоцур (регионы)',
            self::botMo => 'Терминал - мосрег (МО)',
            self::botOrion => 'Терминал - орион (калуга)',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(bool $withPlaceholder = false): array
    {
        $options = [];

        if ($withPlaceholder) {
            $options[''] = 'Выберите аккаунт';
        }

        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
