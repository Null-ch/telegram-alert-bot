<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Enums\BotName;
use App\Models\ChatGroup;
use App\Models\GroupChat;
use MoonShine\UI\Fields\Text;
use MoonShine\AssetManager\Raw;
use MoonShine\Support\AlpineJs;
use MoonShine\UI\Fields\Select;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Support\Enums\JsEvent;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Contracts\UI\ComponentContract;

class ChatGroupPage extends Page
{
    private ?ChatGroup $chatGroup = null;
    private bool $resolved = false;

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
        return $this->editingChatGroup() ? 'Редактировать группу чатов' : 'Создать группу чатов';
    }

    private function editingChatGroup(): ?ChatGroup
    {
        if ($this->resolved) {
            return $this->chatGroup;
        }

        $this->resolved = true;
        $id = request()->query('id');

        if (!$id) {
            return $this->chatGroup = null;
        }

        return $this->chatGroup = ChatGroup::with('groupChats')->find($id);
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        $chatGroup = $this->editingChatGroup();
        $account = $chatGroup->account ?? '';

        $chatOptions = [];
        $selectedChatIds = [];

        if ($chatGroup) {
            $chatOptions = GroupChat::where('account', $account)
                ->orderBy('title', 'ASC')
                ->pluck('title', 'id')
                ->map(fn ($title) => "{$title} ({$account})")
                ->toArray();

            $selectedChatIds = $chatGroup->groupChats->pluck('id')->all();
        }

        $action = $chatGroup
            ? route('chat-groups.update', $chatGroup->id)
            : route('chat-groups.store');

        $chatsSelectAttributes = [
            'id' => 'chatGroupChatsSelect',
            'class' => 'custom-select',
        ];

        if (!$chatGroup) {
            $chatsSelectAttributes['disabled'] = 'disabled';
        }

        return [
            FormBuilder::make($action)
                ->fields([
                    Raw::make(view('admin.scripts.chat-group-js', [
                        'selectedChatIds' => $selectedChatIds,
                    ])->render()),
                    Text::make('Название', 'title')->required()->default($chatGroup->title ?? ''),
                    Select::make('Аккаунт', 'account')
                        ->options(BotName::options(withPlaceholder: !$chatGroup))
                        ->default($account)
                        ->required()
                        ->customAttributes(['id' => 'chat-group-account-select'])
                        ->onChangeEvent(
                            AlpineJs::event(JsEvent::FRAGMENT_UPDATED, 'selects'),
                            exclude: ['title']
                        ),
                    Select::make('Чаты', 'chat_ids')
                        ->options($chatOptions)
                        ->multiple()
                        ->native()
                        ->customAttributes($chatsSelectAttributes),
                ])
                ->submit('Сохранить', ['class' => 'btn btn-primary']),
        ];
    }
}
