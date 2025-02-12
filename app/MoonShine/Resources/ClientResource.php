<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Client;
use MoonShine\UI\Fields\ID;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Text;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Laravel\Enums\Action;
use App\MoonShine\Pages\Client\ClientFormPage;
use MoonShine\Laravel\Resources\ModelResource;
use App\MoonShine\Pages\Client\ClientIndexPage;
use App\MoonShine\Pages\Client\ClientDetailPage;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;

/**
 * @extends ModelResource<Client, ClientIndexPage, ClientFormPage, ClientDetailPage>
 */
class ClientResource extends ModelResource
{
    protected string $model = Client::class;

    protected string $title = 'Пользователи';
    public string $column = 'id';

    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [
            ClientIndexPage::class,
            ClientFormPage::class,
            ClientDetailPage::class,
        ];
    }
    public function metrics(): array 
    {
        return [
            ValueMetric::make('Всего пользователей')
                ->value(Client::count())
        ];
    } 
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make(__('ФИО'), 'full_name'),
            Text::make(__('ID Телеграм'), 'tg_id'),
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

    protected function activeActions(): ListOf
    {
        return parent::activeActions()->except(Action::DELETE)->except(Action::CREATE);
    }
    /**
     * @param Client $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
