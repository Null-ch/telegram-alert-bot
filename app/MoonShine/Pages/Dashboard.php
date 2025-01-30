<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use Carbon\Carbon;
use App\Models\Appeal;
use App\Models\Client;
use MoonShine\Laravel\Pages\Page;
use MoonShine\UI\Fields\DateRange;
use MoonShine\Contracts\UI\ComponentContract;
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
        return [
            ValueMetric::make('Всего обращений')
                ->value(Appeal::count())
                ->columnSpan(6),
            ValueMetric::make('Всего уникальных пользователей')
                ->value(Client::count())
                ->columnSpan(6),
            LineChartMetric::make('Обращения')
                ->line([
                    'Обращения' => Appeal::query()
                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->selectRaw('DATE_FORMAT(created_at, "%d.%m.%Y") as date, COUNT(*) as count')
                        ->groupBy(['date'])
                        ->pluck('count', 'date')
                        ->toArray()
                ]),

        ];
    }
}
