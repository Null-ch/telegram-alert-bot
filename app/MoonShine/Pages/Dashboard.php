<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use Carbon\Carbon;
use App\Models\Appeal;
use App\Models\Client;
use App\Models\MessageReaction;
use MoonShine\UI\Fields\ID;
use PhpParser\Node\Stmt\Block;
use MoonShine\AssetManager\Raw;
use MoonShine\Laravel\Pages\Page;
use MoonShine\UI\Fields\DateRange;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Apexcharts\Components\LineChartMetric;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;

class Dashboard extends Page
{
    /**
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    public function getTitle(): string
    {
        return $this->title ?: 'Dashboard';
    }

    public function filters(): array
    {
        return [
            DateRange::make('Дата обращения', 'created_at'),
        ];
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Статистика по реакциям за текущий месяц
        $monthReactions = MessageReaction::with('employee')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->whereNotNull('employee_id')
            ->selectRaw('employee_id, COUNT(*) as reaction_count')
            ->groupBy('employee_id')
            ->get()
            ->map(function ($item) {
                $employeeName = 'Неизвестно';
                $employeeTag = '-';
                
                if ($item->employee) {
                    $parts = array_filter([$item->employee->first_name, $item->employee->last_name]);
                    $employeeName = !empty($parts) ? implode(' ', $parts) : 'Неизвестно';
                    $employeeTag = $item->employee->tag ?? '-';
                }
                
                return [
                    'employee' => $employeeName,
                    'tag' => $employeeTag,
                    'count' => $item->reaction_count,
                ];
            })
            ->sortByDesc('count')
            ->values();

        // Статистика по реакциям за текущую неделю
        $weekReactions = MessageReaction::with('employee')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->whereNotNull('employee_id')
            ->selectRaw('employee_id, COUNT(*) as reaction_count')
            ->groupBy('employee_id')
            ->get()
            ->map(function ($item) {
                $employeeName = 'Неизвестно';
                $employeeTag = '-';
                
                if ($item->employee) {
                    $parts = array_filter([$item->employee->first_name, $item->employee->last_name]);
                    $employeeName = !empty($parts) ? implode(' ', $parts) : 'Неизвестно';
                    $employeeTag = $item->employee->tag ?? '-';
                }
                
                return [
                    'employee' => $employeeName,
                    'tag' => $employeeTag,
                    'count' => $item->reaction_count,
                ];
            })
            ->sortByDesc('count')
            ->values();

        // Формируем HTML таблицы для месяца
        $monthTableHtml = '<table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Сотрудник</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Тег</th>
                    <th style="padding: 0.75rem; text-align: right; font-weight: 600;">Количество</th>
                </tr>
            </thead>
            <tbody>';
        
        if ($monthReactions->isEmpty()) {
            $monthTableHtml .= '<tr><td colspan="3" style="padding: 1rem; text-align: center; color: #6b7280;">Нет данных</td></tr>';
        } else {
            foreach ($monthReactions as $reaction) {
                $monthTableHtml .= '<tr style="border-bottom: 1px solid #f3f4f6;">
                    <td style="padding: 0.75rem;">' . htmlspecialchars($reaction['employee']) . '</td>
                    <td style="padding: 0.75rem;">' . htmlspecialchars($reaction['tag']) . '</td>
                    <td style="padding: 0.75rem; text-align: right; font-weight: 600;">' . $reaction['count'] . '</td>
                </tr>';
            }
        }
        
        $monthTableHtml .= '</tbody></table>';

        // Формируем HTML таблицы для недели
        $weekTableHtml = '<table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Сотрудник</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Тег</th>
                    <th style="padding: 0.75rem; text-align: right; font-weight: 600;">Количество</th>
                </tr>
            </thead>
            <tbody>';
        
        if ($weekReactions->isEmpty()) {
            $weekTableHtml .= '<tr><td colspan="3" style="padding: 1rem; text-align: center; color: #6b7280;">Нет данных</td></tr>';
        } else {
            foreach ($weekReactions as $reaction) {
                $weekTableHtml .= '<tr style="border-bottom: 1px solid #f3f4f6;">
                    <td style="padding: 0.75rem;">' . htmlspecialchars($reaction['employee']) . '</td>
                    <td style="padding: 0.75rem;">' . htmlspecialchars($reaction['tag']) . '</td>
                    <td style="padding: 0.75rem; text-align: right; font-weight: 600;">' . $reaction['count'] . '</td>
                </tr>';
            }
        }
        
        $weekTableHtml .= '</tbody></table>';

        return [
            Grid::make([
                Column::make([
                    Box::make('Общие показатели', [
                        ValueMetric::make('Всего обращений')
                            ->value(Appeal::count())
                            ->columnSpan(6),
                        ValueMetric::make('Всего уникальных пользователей')
                            ->value(Client::count())
                            ->columnSpan(6),
                    ]),
                ], 6),
                Column::make([
                    Box::make('Показатели за текущий месяц', [
                        ValueMetric::make('Всего обращений')
                            ->value(Appeal::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count())
                            ->columnSpan(6),
                        ValueMetric::make('Всего уникальных пользователей')
                            ->value(Client::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count())
                            ->columnSpan(6),
                    ]),
                ], 6),
                Column::make([
                    Box::make('Реакции на сообщения за текущий месяц', [
                        Raw::make($monthTableHtml),
                    ]),
                ], 6),
                Column::make([
                    Box::make('Реакции на сообщения за неделю', [
                        Raw::make($weekTableHtml),
                    ]),
                ], 6),
                Raw::make(
                    <<<HTML
                    <!-- HELPDESKEDDY WIDGETS START -->
                    <script 
                        src="//cdn5.helpdeskeddy.com//js/contact-widget.js" 
                        id="hde-contact-widget" 
                        data-assets-host="//cdn5.helpdeskeddy.com/" 
                        data-host="nalitek.helpdeskeddy.com" 
                        data-lang="ru" 
                        defer>
                    </script> 
                    <!-- HELPDESKEDDY WIDGETS END -->
                    HTML
                ),
            ])
        ];
    }
}
