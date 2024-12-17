<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Appeal;
use MoonShine\UI\Fields\ID;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Text;
use MoonShine\Laravel\Pages\Page;
use MoonShine\UI\Fields\DateRange;
use MoonShine\Laravel\Enums\Action;
use App\MoonShine\Pages\Appeal\AppealFormPage;
use MoonShine\Laravel\Resources\ModelResource;
use App\MoonShine\Pages\Appeal\AppealIndexPage;
use App\MoonShine\Pages\Appeal\AppealDetailPage;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;

/**
 * @extends ModelResource<Appeal, AppealIndexPage, AppealFormPage, AppealDetailPage>
 */
class AppealResource extends ModelResource
{
    protected string $model = Appeal::class;
    protected string $title = 'Обращения';
    public static string $orderField = 'id';
    public string $column = 'client_id';
    protected array $with = ['client'];
    protected bool $createInModal = false;
    protected bool $editInModal = false;
    protected bool $showInModal = true;
    protected bool $paginate = true;
    protected int $itemsPerPage = 10;
    protected bool $simplePaginate = true;

    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [
            AppealIndexPage::class,
            AppealFormPage::class,
            AppealDetailPage::class,
        ];
    }

    public function metrics(): array
    {
        return [
            ValueMetric::make('Всего обращений')
                ->value(Appeal::count())
        ];
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Содержимое'), 'text'),
            Text::make(__('Пользователь'), 'client_id', static fn(Appeal $appeal) => $appeal->client->full_name),
            Text::make(__('Дата'), 'created_at'),
        ];
    }

    protected function detailFields(): iterable
    {
        return $this->indexFields();
    }

    public function filters(): array
    {
        return [
            DateRange::make('Дата обращения', 'created_at'),
        ];
    }

    protected function activeActions(): ListOf
    {
        return parent::activeActions()->except(Action::DELETE)->except(Action::UPDATE)->except(Action::CREATE);
    }

    /**
     * @param Appeal $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
