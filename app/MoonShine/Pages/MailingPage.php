<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use MoonShine\UI\Fields\Select;
use MoonShine\Laravel\Pages\Page;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\EasyMde\Fields\Markdown;

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
                    Select::make('Аккаунт', 'account')
                        ->options([
                            'botInfocur' => 'Терминал - инфоцур (регионы)',
                            'botMo' => 'Терминал - мосрег (МО)',
                            'botOrion' => 'Терминал - орион (калуга)',
                            'test' => 'Тестовый',
                        ])->required(),
                ])
                ->submit('Отправить', ['class' => 'btn btn-primary'])
        ];
    }
}
