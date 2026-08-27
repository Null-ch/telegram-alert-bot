<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Enums\BotName;
use App\Models\ChatGroup;
use MoonShine\UI\Fields\ID;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Select;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Laravel\Enums\Action;
use MoonShine\UI\Components\ActionButton;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\UI\ActionButtonContract;
use App\MoonShine\Pages\ChatGroup\ChatGroupFormPage;
use App\MoonShine\Pages\ChatGroup\ChatGroupIndexPage;
use App\MoonShine\Pages\ChatGroup\ChatGroupDetailPage;

/**
 * @extends ModelResource<ChatGroup, ChatGroupIndexPage, ChatGroupFormPage, ChatGroupDetailPage>
 */
class ChatGroupResource extends ModelResource
{
    protected string $model = ChatGroup::class;

    protected string $title = 'Группы чатов';

    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [
            ChatGroupIndexPage::class,
            ChatGroupFormPage::class,
            ChatGroupDetailPage::class,
        ];
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Select::make('Аккаунт', 'account')
                ->options(BotName::options())
                ->required(),
            Text::make('Название', 'title')->required(),
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
        return parent::activeActions()->except(Action::CREATE)->except(Action::UPDATE);
    }

    protected function modifyCreateButton(ActionButtonContract $button): ActionButtonContract
    {
        return ActionButton::make('Создать', '/admin/page/chat-group-page');
    }

    protected function modifyUpdateButton(ActionButtonContract $button): ActionButtonContract
    {
        return ActionButton::make('Изменить', '/admin/page/chat-group-page?id={id}');
    }

    /**
     * @param ChatGroup $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
