<?php

namespace App\Console\Commands;

use App\Models\MessageReaction;
use App\Models\Employee;
use Illuminate\Console\Command;
use Carbon\Carbon;
use GuzzleHttp\Client;

class WeeklyReactionStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:weekly-reaction-stats {start?} {end?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Собирает статистику по реакциям за текущую неделю и отправляет в Telegram чат';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startInput = $this->argument('start');
        $endInput   = $this->argument('end');

        if ($startInput && $endInput) {
            $startOfWeek = Carbon::parse($startInput)->startOfDay();
            $endOfWeek   = Carbon::parse($endInput)->endOfDay();
        } else {
            $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endOfWeek   = Carbon::now()->endOfWeek(Carbon::SUNDAY);
        }

        $this->info("Сбор статистики за период: {$startOfWeek->format('d.m.Y')} - {$endOfWeek->format('d.m.Y')}");

        $stats = MessageReaction::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->with('employee')
            ->get()
            ->groupBy('employee_id')
            ->map(function ($reactions) {
                $firstReaction = $reactions->first();
                $employee = $firstReaction->employee;

                if ($employee) {
                    $name = trim(($employee->first_name ?? '') . ' ' . ($employee->last_name ?? ''));
                    $name = $name ?: ($employee->tag ?? 'Без имени');
                } else {
                    $name = 'Неизвестный сотрудник';
                }

                return [
                    'name' => $name,
                    'count' => $reactions->count()
                ];
            })
            ->values()
            ->sortByDesc('count')
            ->values()
            ->toArray();

        if (empty($stats)) {
            $message = "📊 Статистика по заявкам за неделю\n\n";
            $message .= "Период: {$startOfWeek->format('d.m.Y')} - {$endOfWeek->format('d.m.Y')}\n\n";
            $message .= "За этот период нет отработанных заявок.";
        } else {
            $message = "📊 Статистика по заявкам за неделю\n\n";
            $message .= "Период: {$startOfWeek->format('d.m.Y')} - {$endOfWeek->format('d.m.Y')}\n\n";

            foreach ($stats as $index => $stat) {
                $message .= ($index + 1) . ". {$stat['name']} - {$stat['count']} шт.\n";
            }
        }

        // Отправляем сообщение в Telegram
        $chatId = '-1001550218774';
        $threadId = 83189;
        $botToken = env('TELEGRAM_TEST_BOT_TOKEN');

        try {
            $client = new Client();
            $client->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'form_params' => [
                    'chat_id' => $chatId,
                    'text' => $message,
                    'message_thread_id' => $threadId,
                    'parse_mode' => 'HTML'
                ]
            ]);

            $this->info('Статистика успешно отправлена в Telegram!');
        } catch (\Exception $e) {
            $this->error('Ошибка при отправке статистики: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
