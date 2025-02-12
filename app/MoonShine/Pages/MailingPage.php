<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\Select;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Grid;
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
                    Text::make('Текст', 'message')->required(),
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
