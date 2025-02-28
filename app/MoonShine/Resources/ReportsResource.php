<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Report;
use MoonShine\UI\Fields\ID;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Text;
use MoonShine\Laravel\Pages\Page;
use MoonShine\UI\Fields\DateRange;
use MoonShine\Laravel\Enums\Action;
use Illuminate\Database\Eloquent\Model;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\ActionButton;
use MoonShine\Laravel\Resources\ModelResource;
use App\MoonShine\Pages\Reports\ReportsFormPage;
use MoonShine\Contracts\UI\ActionButtonContract;
use App\MoonShine\Pages\Reports\ReportsIndexPage;
use App\MoonShine\Pages\Reports\ReportsDetailPage;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;

/**
 * @extends ModelResource<Report, ReportsIndexPage, ReportsFormPage, ReportsDetailPage>
 */
class ReportsResource extends ModelResource
{
    protected string $model = Report::class;

    protected string $title = 'Отчеты';

    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [
            ReportsIndexPage::class,
            ReportsFormPage::class,
            ReportsDetailPage::class,
        ];
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Название'), 'title'),
            Text::make(__('Дата создания'), 'created_at'),
        ];
    }

    protected function indexButtons(): ListOf
    {
        return parent::indexButtons()
            ->prepend(
                ActionButton::make(
                    'Скачать',
                    fn(Model $item) => route('reports.download', $item->getKey())
                )->primary()
            );
    }

    protected function activeActions(): ListOf
    {
        return parent::activeActions()->except(Action::UPDATE)->except(Action::CREATE)->except(Action::VIEW);
    }

    /**
     * @param Report $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }

    protected function modifyCreateButton(ActionButtonContract $button): ActionButtonContract
    {
        return ActionButton::make('Создать')
            ->inModal(
                fn() => 'Выберите период за который выгрузить обращения',
                fn() => (string) FormBuilder::make(route('reports.generate'))
                    ->fields([
                        DateRange::make('Период')->required(),
                    ])
                    ->submit('Сформировать', [
                        'class' => 'btn-primary',
                    ])
            )
            ->primary();
    }
}
