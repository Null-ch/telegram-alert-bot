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
            Raw::make(<<<HTML
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div>
            HTML),
            ActionButton::make('Экспорт данных', '#')
                ->primary()
                ->customAttributes([
                    'onclick' => "exportMessageReactions('{$exportRoute}'); return false;",
                    'style' => 'cursor: pointer;'
                ]),
            Raw::make(<<<HTML
                    </div>
                    <div>
            HTML),
            ...parent::topLayer(),
            Raw::make(<<<HTML
                    </div>
                </div>
            HTML),
            Raw::make(<<<JS
                <script>
                    function exportMessageReactions(exportUrl) {
                        const fullUrl = new URL(exportUrl, window.location.origin);

                        // 1. Собираем параметры из URL текущей страницы
                        const urlParams = new URLSearchParams(window.location.search);
                        urlParams.forEach((value, key) => {
                            fullUrl.searchParams.set(key, value);
                        });

                        // 2. Дополняем значениями из полей фильтра (если не в URL)
                        const filterInputs = document.querySelectorAll(
                            'input[name*="created_at"], input[name*="filter"]'
                        );
                        filterInputs.forEach(function(input) {
                            if (input.name && input.value) {
                                fullUrl.searchParams.set(input.name, input.value);
                            }
                        });

                        // 3. Для DateRange: ищем пару from/to и передаём в формате filter[created_at][from/to]
                        const dateFromInput = document.querySelector(
                            'input[name="filter[created_at][from]"], input[name="filters[created_at][from]"]'
                        );
                        const dateToInput = document.querySelector(
                            'input[name="filter[created_at][to]"], input[name="filters[created_at][to]"]'
                        );
                        if (dateFromInput && dateFromInput.value) {
                            fullUrl.searchParams.set('filter[created_at][from]', dateFromInput.value);
                        }
                        if (dateToInput && dateToInput.value) {
                            fullUrl.searchParams.set('filter[created_at][to]', dateToInput.value);
                        }

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


