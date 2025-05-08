<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use Carbon\Carbon;
use App\Models\Appeal;
use App\Models\Client;
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
