<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\MessageReaction;

use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\UI\Components\ActionButton;
use MoonShine\AssetManager\Raw;
use Throwable;

class MessageReactionIndexPage extends IndexPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        $exportRoute = route('message-reactions.export');
        
        return [
            ...parent::topLayer(),
            ActionButton::make('Экспорт данных', '#')
                ->primary()
                ->icon('heroicons.download')
                ->customAttributes(['data-export-url' => $exportRoute])
                ->onClick('exportMessageReactions(this)'),
            Raw::make(<<<JS
                <script>
                    function exportMessageReactions(button) {
                        const exportUrl = button.getAttribute('data-export-url');
                        const urlParams = new URLSearchParams(window.location.search);
                        const fullUrl = new URL(exportUrl, window.location.origin);
                        
                        // Копируем все параметры фильтрации из текущего URL
                        urlParams.forEach((value, key) => {
                            fullUrl.searchParams.append(key, value);
                        });
                        
                        window.location.href = fullUrl.toString();
                    }
                </script>
            JS),
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer(),
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer(),
        ];
    }
}


