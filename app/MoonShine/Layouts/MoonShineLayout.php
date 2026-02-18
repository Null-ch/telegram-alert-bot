<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Laravel\Components\Layout\{Locales, Notifications, Profile, Search};
use MoonShine\UI\Components\{Breadcrumbs,
    Components,
    Layout\Flash,
    Layout\Div,
    Layout\Body,
    Layout\Burger,
    Layout\Content,
    Layout\Footer,
    Layout\Head,
    Layout\Favicon,
    Layout\Assets,
    Layout\Meta,
    Layout\Header,
    Layout\Html,
    Layout\Layout,
    Layout\Logo,
    Layout\Menu,
    Layout\Sidebar,
    Layout\ThemeSwitcher,
    Layout\TopBar,
    Layout\Wrapper,
    When};
use App\MoonShine\Resources\AppealResource;
use MoonShine\MenuManager\MenuItem;
use MoonShine\MenuManager\MenuGroup;
use App\MoonShine\Resources\IgnoreListResource;
use App\MoonShine\Resources\ClientResource;
use App\MoonShine\Resources\GroupChatResource;
use App\MoonShine\Resources\MailingResource;
use App\MoonShine\Pages\MailingPage;
use App\MoonShine\Resources\ReportsResource;
final class MoonShineLayout extends AppLayout
{
    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        return [
            MenuGroup::make('Alert-Bot', [
                MenuItem::make('Обращения', AppealResource::class),
                MenuItem::make('Отчеты', ReportsResource::class),
                MenuItem::make('Пользователи', ClientResource::class),
                MenuItem::make('Игнор-лист', IgnoreListResource::class),
                MenuItem::make('Отработка обращений', MessageReactionResource::class),
                MenuGroup::make('Рассылки', [
                    MenuItem::make('Рассылки', MailingResource::class),
                    MenuItem::make('Групповые чаты', GroupChatResource::class),
                    MenuItem::make('Создать рассылку', MailingPage::class)
                ]),
            ]),
            ...parent::menu(),
        ];
    }

    protected function getFooterMenu(): array
    {
        return [
            'https://alert-bot.ru/admin' => 'Alert-Bot',
        ];
    }
 
    protected function getFooterCopyright(): string
    {
        return 'Telegram Alert-Bot';
    }

    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }

    public function build(): Layout
    {
        return parent::build();
    }
}
