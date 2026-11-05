<?php

declare(strict_types=1);

namespace TypiCMS\Modules\Events\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Events\Filters\FilterRegistrations;
use TypiCMS\Modules\Events\Models\Event;
use TypiCMS\Modules\Events\Models\Registration;

final class RegistrationsApiController extends BaseApiController
{
    /** @return LengthAwarePaginator<int, Registration> */
    public function index(Request $request, Event $event): LengthAwarePaginator
    {
        $query = Registration::query()->selectFields()->where('event_id', $event->id);

        return QueryBuilder::for($query)
            ->allowedSorts('created_at', 'first_name', 'last_name', 'email', 'locale', 'number_of_people', 'message')
            ->allowedFilters(
                AllowedFilter::custom(
                    'created_at,first_name,last_name,email,locale,number_of_people,message',
                    new FilterRegistrations,
                ),
            )
            ->paginate($request->integer('per_page'));
    }

    public function destroy(Event $event, Registration $registration): JsonResponse
    {
        $registration->delete();

        return response()->json(status: 204);
    }
}
