<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use MoonShine\UI\Fields\File;
use MoonShine\AssetManager\Raw;
use MoonShine\Support\AlpineJs;
use MoonShine\UI\Fields\Select;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Support\Enums\JsEvent;
use MoonShine\EasyMde\Fields\Markdown;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Contracts\UI\ComponentContract;

class MailingPage extends Page
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
        return $this->title ?: 'Создать рассылку';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        return [
            FormBuilder::make(route('send.mailing'))
                ->fields([
                    Markdown::make('Текст', 'message')->required(),
                    Raw::make(view('admin.scripts.mailing-js')->render()),
                    File::make('Добавить файл')->customAttributes(['name' => 'file']),
                    Select::make('Аккаунт', 'account')
                        ->options([
                            '' => 'Выберите аккаунт',
                            'test' => 'Тестовый',
                            'botInfocur' => 'Терминал - инфоцур (регионы)',
                            'botMo' => 'Терминал - мосрег (МО)',
                            'botOrion' => 'Терминал - орион (калуга)',
                        ])
                        ->required()
                        ->customAttributes(['id' => 'account-select'])
                        ->onChangeEvent(
                            AlpineJs::event(JsEvent::FRAGMENT_UPDATED, 'selects'),
                            exclude: ['message', 'file']
                        ),
                    Select::make('Выбор чатов', 'chat_ids')
                        ->options([])
                        ->multiple()
                        ->native()
                        ->customAttributes([
                            'id' => 'adminGroupChats',
                            'disabled' => 'disabled',
                            'class' => 'custom-select',
                        ]),
                ])
                ->submit('Отправить', ['class' => 'btn btn-primary']),
        ];
    }
}
