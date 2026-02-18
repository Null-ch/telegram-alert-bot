<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\MoonShine\Resources\MoonShineUserResource;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use App\MoonShine\Resources\AppealResource;
use App\MoonShine\Resources\IgnoreListResource;
use App\MoonShine\Resources\ClientResource;
use App\MoonShine\Resources\GroupChatResource;
use App\MoonShine\Resources\MailingResource;
use App\MoonShine\Pages\MailingPage;
use App\MoonShine\Resources\ReportsResource;
use App\MoonShine\Resources\MessageReactionResource;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  MoonShine  $core
     * @param  MoonShineConfigurator  $config
     *
     */
    public function boot(CoreContract $core, ConfiguratorContract $config): void
    {
        // $config->authEnable();

        $core
            ->resources([
                AppealResource::class,
                IgnoreListResource::class,
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                ClientResource::class,
                GroupChatResource::class,
                MailingResource::class,
                ReportsResource::class,
                MessageReactionResource::class,
            ])
            ->pages([
                ...$config->getPages(),
                MailingPage::class,
            ])
        ;
    }
}
