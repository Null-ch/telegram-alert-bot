<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\MessageReaction;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
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


