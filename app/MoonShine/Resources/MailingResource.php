<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Mailing;
use MoonShine\UI\Fields\ID;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Email;
use MoonShine\Support\AlpineJs;
use MoonShine\UI\Fields\Select;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Laravel\Enums\Action;
use App\MoonShine\Pages\MailingPage;
use MoonShine\Support\Enums\JsEvent;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\ActionButton;
use MoonShine\Laravel\Resources\ModelResource;
use App\MoonShine\Pages\Mailing\MailingFormPage;
use MoonShine\Contracts\UI\ActionButtonContract;
use App\MoonShine\Pages\Mailing\MailingIndexPage;
use App\MoonShine\Pages\Mailing\MailingDetailPage;

/**
 * @extends ModelResource<Mailing, MailingIndexPage, MailingFormPage, MailingDetailPage>
 */
class MailingResource extends ModelResource
{
    protected string $model = Mailing::class;

    protected string $title = 'Рассылки';

    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [
            MailingIndexPage::class,
            MailingFormPage::class,
            MailingDetailPage::class,
        ];
    }

    protected function indexFields(): iterable
    {
        return [
            Text::make('Сообщение', 'message')->required(),
            Select::make('Аккаунт', 'account')
                ->options([
                    '' => 'Выберите аккаунт',
                    'test' => 'Тестовый',
                    'botInfocur' => 'Терминал - инфоцур (регионы)',
                    'botMo' => 'Терминал - мосрег (МО)',
                    'botOrion' => 'Терминал - орион (калуга)',
                ])->required(),
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
        return parent::activeActions()->except(Action::DELETE)->except(Action::UPDATE)->except(Action::CREATE);
    }

    protected function modifyCreateButton(ActionButtonContract $button): ActionButtonContract
    {
        return ActionButton::make('Создать', '/admin/page/mailing-page');
    }

    /**
     * @param Mailing $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
