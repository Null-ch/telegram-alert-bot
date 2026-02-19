<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Carbon\Carbon;
use App\Models\MessageReaction;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\DateRange;
use MoonShine\Support\ListOf;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Laravel\Enums\Action;
use MoonShine\Laravel\Resources\ModelResource;
use App\MoonShine\Pages\MessageReaction\MessageReactionIndexPage;
use App\MoonShine\Pages\MessageReaction\MessageReactionFormPage;
use App\MoonShine\Pages\MessageReaction\MessageReactionDetailPage;

/**
 * @extends ModelResource<MessageReaction, MessageReactionIndexPage, MessageReactionFormPage, MessageReactionDetailPage>
 */
class MessageReactionResource extends ModelResource
{
    protected string $model = MessageReaction::class;

    protected string $title = 'Реакции на сообщения';

    public static string $orderField = 'id';

    public string $column = 'id';

    /**
     * Eager load relations.
     *
     * @var array<int, string>
     */
    protected array $with = ['employee'];

    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [
            MessageReactionIndexPage::class,
            MessageReactionFormPage::class,
            MessageReactionDetailPage::class,
        ];
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Аккаунт'), 'account'),
            Text::make(__('Сотрудник (ФИО)'), 'employee_id', static fn (MessageReaction $reaction): string => $reaction->employee
                ? trim(($reaction->employee->last_name ?? '') . ' ' . ($reaction->employee->first_name ?? ''))
                : ''
            ),
            Text::make(__('Сотрудник (тег)'), 'employee_id', static fn (MessageReaction $reaction): string => $reaction->employee->tag ?? ''),
            Text::make(__('Дата'), 'created_at'),
            Text::make(__('Реакция'), 'reaction'),
        ];
    }

    protected function detailFields(): iterable
    {
        return $this->indexFields();
    }

    protected function formFields(): iterable
    {
        // Редактирование/создание реакций не требуется
        return [];
    }

    public function filters(): array
    {
        return [
            DateRange::make('Дата', 'date_range'),
        ];
    }

    /**
     * Применение фильтров к запросу
     * Обрабатываем фильтр дат вручную, чтобы избежать ошибки приведения типов
     */
    protected function withFilters($query)
    {
        $query = parent::withFilters($query);
        
        $request = request();
        
        // Получаем фильтры из запроса в разных форматах (MoonShine может передавать по-разному)
        $filters = $request->get('filters', []);
        
        // Обрабатываем фильтр дат вручную (используем кастомное имя 'date_range')
        $dateFrom = $filters['date_range']['from'] ?? 
                   $filters['date_range'][0] ?? 
                   $request->input('date_range.from') ?? 
                   $request->input('date_range[from]') ?? 
                   $request->query('date_range.from') ?? 
                   $request->query('date_range[from]') ?? 
                   null;
                   
        $dateTo = $filters['date_range']['to'] ?? 
                 $filters['date_range'][1] ?? 
                 $request->input('date_range.to') ?? 
                 $request->input('date_range[to]') ?? 
                 $request->query('date_range.to') ?? 
                 $request->query('date_range[to]') ?? 
                 null;
        
        if ($dateFrom) {
            $query->where('created_at', '>=', Carbon::parse($dateFrom)->startOfDay());
        }
        if ($dateTo) {
            $query->where('created_at', '<=', Carbon::parse($dateTo)->endOfDay());
        }
        
        return $query;
    }

    protected function activeActions(): ListOf
    {
        // Делаем ресурс только для просмотра
        return parent::activeActions()
            ->except(Action::DELETE)
            ->except(Action::UPDATE)
            ->except(Action::CREATE);
    }

    /**
     * @param MessageReaction $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}


