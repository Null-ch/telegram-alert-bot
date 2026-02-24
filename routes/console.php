<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:weekly-reaction-stats')
    ->timezone('Europe/Moscow')
    ->sundays()
    ->at('21:00');