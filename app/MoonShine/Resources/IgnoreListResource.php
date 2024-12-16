<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\IgnoreList;
use App\MoonShine\Pages\IgnoreList\IgnoreListIndexPage;
use App\MoonShine\Pages\IgnoreList\IgnoreListFormPage;
use App\MoonShine\Pages\IgnoreList\IgnoreListDetailPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Pages\Page;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<IgnoreList, IgnoreListIndexPage, IgnoreListFormPage, IgnoreListDetailPage>
 */
class IgnoreListResource extends ModelResource
{
    protected string $model = IgnoreList::class;
    public static string $orderField = 'id';
    protected bool $simplePaginate = true;
    protected bool $createInModal = true;
    protected bool $editInModal = true;

    protected string $title = 'Игнор-лист';

    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [
            IgnoreListIndexPage::class,
            IgnoreListFormPage::class,
            IgnoreListDetailPage::class,
        ];
    }
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make(__('ID Telegram'), 'tg_id'),
        ];
    }

    protected function detailFields(): iterable
    {
        return $this->indexFields();
    }

    protected function formFields(): iterable
    {
        return $this->indexFields();
    }
    /**
     * @param IgnoreList $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
