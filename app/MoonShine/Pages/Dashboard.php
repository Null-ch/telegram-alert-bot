<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Appeal;
use App\Models\Client;
use MoonShine\Apexcharts\Components\LineChartMetric;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
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

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        return [
            LineChartMetric::make('Всего обращений')->getIconValue(Appeal::count()),
            ValueMetric::make('Всего обращений')
                ->value(Appeal::count()),
            ValueMetric::make('Всего уникальных пользователей')
                ->value(Client::count())
        ];
    }
}
