<?php

declare(strict_types=1);

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use TypiCMS\Modules\Core\Support\ModuleRoutes;
use TypiCMS\Modules\Events\Http\Controllers\AdminController;
use TypiCMS\Modules\Events\Http\Controllers\ApiController;
use TypiCMS\Modules\Events\Http\Controllers\PublicController;
use TypiCMS\Modules\Events\Http\Controllers\RegistrationsAdminController;
use TypiCMS\Modules\Events\Http\Controllers\RegistrationsApiController;

/*
 * Front office routes
 */
ModuleRoutes::group('events', function (Router $router): void {
    $router->get('/', [PublicController::class, 'index']);
    $router->get('past', [PublicController::class, 'past']);
    $router->get('{slug}', [PublicController::class, 'show']);
    $router->get('{slug}/ics', [PublicController::class, 'ics']);
    $router
        ->middleware('auth')
        ->group(function (Router $router): void {
            $router->get('{slug}/registration', [
                PublicController::class,
                'showRegistrationForm',
            ]);
            $router
                ->post('{slug}/register', [PublicController::class, 'register']);
            $router->get('{slug}/registered', [PublicController::class, 'registered']);
        });
});

/*
 * Admin routes
 */
Route::middleware('admin')
    ->prefix('admin')
    ->name('admin::')
    ->group(function (Router $router): void {
        $router->get('events', [AdminController::class, 'index'])->name('index-events')->middleware('can:read events');
        $router
            ->get('events/export', [AdminController::class, 'export'])
            ->name('export-events')
            ->middleware('can:read events');
        $router
            ->get('events/create', [AdminController::class, 'create'])
            ->name('create-event')
            ->middleware('can:create events');
        $router
            ->get('events/{event}/edit', [AdminController::class, 'edit'])
            ->name('edit-event')
            ->middleware('can:read events');
        $router
            ->post('events', [AdminController::class, 'store'])
            ->name('store-event')
            ->middleware('can:create events');
        $router
            ->put('events/{event}', [AdminController::class, 'update'])
            ->name('update-event')
            ->middleware('can:update events');

        $router
            ->get('events/{event}/registrations', [RegistrationsAdminController::class, 'index'])
            ->name('index-registrations')
            ->middleware('can:read registrations');
        $router
            ->get('events/{event}/registrations/export', [RegistrationsAdminController::class, 'export'])
            ->name('export-registrations')
            ->middleware('can:read registrations');
        $router
            ->get('events/{event}/registrations/{registration}/edit', [RegistrationsAdminController::class, 'edit'])
            ->name('edit-registration')
            ->middleware('can:update registrations');
        $router
            ->put('events/{event}/registrations/{registration}', [RegistrationsAdminController::class, 'update'])
            ->name('update-registration')
            ->middleware('can:update registrations');
    });

/*
 * API routes
 */
Route::middleware(['api', 'auth:api'])->prefix('api')->group(function (Router $router): void {
    $router->get('events', [ApiController::class, 'index'])->middleware('can:read events');
    $router->patch('events/{event}', [ApiController::class, 'updatePartial'])->middleware('can:update events');
    $router->post('events/{event}/duplicate', [ApiController::class, 'duplicate'])->middleware('can:create events');
    $router->delete('events/{event}', [ApiController::class, 'destroy'])->middleware('can:delete events');

    $router->get('events/{event}/registrations', [RegistrationsApiController::class, 'index'])->middleware(
        'can:read registrations',
    );
    $router
        ->delete('events/{event}/registrations/{registration}', [RegistrationsApiController::class, 'destroy'])
        ->middleware('can:delete registrations');
});
