<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:weekly-reaction-stats')
    ->timezone('Europe/Moscow')
    ->sundays()
    ->at('21:00');

// Отдельного демона очереди (supervisor) нет, поэтому обрабатываем задания
// (например, отправку рассылок) короткими запусками воркера по расписанию.
Schedule::command('queue:work --stop-when-empty --max-time=50 --tries=1')
    ->everyMinute()
    ->withoutOverlapping();