<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Enums\BotName;
use App\Models\Mailing;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Select;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Laravel\Enums\Action;
use App\MoonShine\Pages\MailingPage;
use MoonShine\Support\Enums\Color;
use MoonShine\UI\Components\ActionButton;
use MoonShine\Laravel\Resources\ModelResource;
use App\MoonShine\Pages\Mailing\MailingFormPage;
use MoonShine\Contracts\UI\ActionButtonContract;
use MoonShine\Contracts\UI\FieldContract;
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
                ->options(BotName::options(withPlaceholder: true))
                ->required(),
            Text::make('Чаты', 'chats_label')->badge($this->queuedAwareColor(Color::GRAY)),
            Text::make('Статус', 'errors_label')->badge($this->errorsBadgeColor()),
        ];
    }

    protected function detailFields(): iterable
    {
        return [
            Text::make('Сообщение', 'message')->required(),
            Select::make('Аккаунт', 'account')
                ->options(BotName::options(withPlaceholder: true))
                ->required(),
            Text::make('Статус', 'errors_label')->badge($this->errorsBadgeColor()),
            Text::make('Успешно отправлено', 'sent_chats_html')->unescape(),
            Text::make('Не удалось отправить', 'failed_chats_html')->unescape(),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Text::make('Сообщение', 'message')->required(),
            Select::make('Аккаунт', 'account')
                ->options(BotName::options(withPlaceholder: true))
                ->required(),
        ];
    }

    protected function activeActions(): ListOf
    {
        return parent::activeActions()->except(Action::DELETE)->except(Action::UPDATE)->except(Action::CREATE);
    }

    protected function modifyCreateButton(ActionButtonContract $button): ActionButtonContract
    {
        return ActionButton::make('Создать', '/admin/page/mailing-page');
    }

    private function errorsBadgeColor(): \Closure
    {
        return $this->queuedAwareColor(function (mixed $value, FieldContract $field): Color {
            $item = $field->getData()?->getOriginal();

            if (! $item instanceof Mailing || ! $item->hasResults()) {
                return Color::GRAY;
            }

            return $item->has_errors ? Color::ERROR : Color::SUCCESS;
        });
    }

    /**
     * Оборачивает цвет бейджа: пока рассылка в очереди, всегда показывает предупреждающий цвет,
     * иначе делегирует вычисление переданному колбэку (или статичному цвету).
     */
    private function queuedAwareColor(Color|\Closure $fallback): \Closure
    {
        return function (mixed $value, FieldContract $field) use ($fallback): Color {
            $item = $field->getData()?->getOriginal();

            if ($item instanceof Mailing && $item->isQueued()) {
                return Color::WARNING;
            }

            return $fallback instanceof \Closure ? $fallback($value, $field) : $fallback;
        };
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
