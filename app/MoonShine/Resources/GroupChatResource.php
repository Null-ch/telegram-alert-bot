<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\GroupChat;
use MoonShine\UI\Fields\ID;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Select;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Laravel\Enums\Action;
use MoonShine\Laravel\Resources\ModelResource;
use App\MoonShine\Pages\GroupChat\GroupChatFormPage;
use App\MoonShine\Pages\GroupChat\GroupChatIndexPage;
use App\MoonShine\Pages\GroupChat\GroupChatDetailPage;

/**
 * @extends ModelResource<GroupChat, GroupChatIndexPage, GroupChatFormPage, GroupChatDetailPage>
 */
class GroupChatResource extends ModelResource
{
    protected string $model = GroupChat::class;

    protected string $title = 'Групповые чаты';

    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [
            GroupChatIndexPage::class,
            GroupChatFormPage::class,
            GroupChatDetailPage::class,
        ];
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Select::make('Аккаунт', 'account')
                ->options([
                    'botInfocur' => 'Терминал - инфоцур (регионы)',
                    'botMo' => 'Терминал - мосрег (МО)',
                    'botOrion' => 'Терминал - орион (калуга)',
                    'test' => 'Тестовый',
                ])->required(),
            Text::make(__('Название чата'), 'title')->required(),
            Text::make(__('ID чата'), 'chat_id')->required(),
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
     * @param GroupChat $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
