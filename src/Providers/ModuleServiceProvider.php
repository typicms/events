<?php

declare(strict_types=1);

namespace TypiCMS\Modules\Events\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Override;
use TypiCMS\Modules\Events\Composers\SidebarViewComposer;

class ModuleServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/events.php', 'typicms.modules.events');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/events.php');

        $this->loadViewsFrom([
            resource_path('views/admin'),
            __DIR__.'/../../resources/views/admin',
        ], 'admin');

        $this->loadViewsFrom([
            resource_path('views/public'),
            __DIR__.'/../../resources/views/public',
        ], 'public');

        $this->publishes([
            __DIR__.'/../../database/migrations/create_events_table.php.stub' => getMigrationFileName(
                'create_events_table',
            ),
        ], 'typicms-migrations');
        $this->publishes([
            __DIR__.'/../../database/migrations/create_registrations_table.php.stub' => getMigrationFileName(
                'create_registrations_table',
            ),
        ], 'typicms-migrations');
        $this->publishes([
            __DIR__.'/../../resources/views/admin/events' => resource_path('views/admin/events'),
        ], ['typicms-views', 'typicms-admin-views', 'typicms-admin-events-views']);
        $this->publishes([
            __DIR__.'/../../resources/views/public/events' => resource_path('views/public/events'),
        ], ['typicms-views', 'typicms-public-views', 'typicms-public-events-views']);
        $this->publishes([
            __DIR__.'/../../resources/views/mail/events' => resource_path('views/mail/events'),
        ], ['typicms-views', 'typicms-mail-views', 'typicms-mail-events-views']);
        $this->publishes([__DIR__.'/../../resources/scss' => resource_path('scss')], 'typicms-resources');

        View::composer('admin::core._sidebar', SidebarViewComposer::class);

    }
}
