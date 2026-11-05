<?php

declare(strict_types=1);

namespace TypiCMS\Modules\Events\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Filters\FilterOr;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Events\Models\Event;

final class ApiController extends BaseApiController
{
    /** @return LengthAwarePaginator<int, Event> */
    public function index(Request $request): LengthAwarePaginator
    {
        $query = Event::query()
            ->selectFields()
            ->addSelect([
                'registration_count' => DB::table('registrations')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('events.id', 'registrations.event_id'),
            ]);

        return QueryBuilder::for($query)
            ->allowedSorts('status_translated', 'start_date', 'end_date', 'title_translated')
            ->allowedFilters(
                AllowedFilter::custom('title', new FilterOr),
            )
            ->allowedIncludes('image')
            ->paginate($request->integer('per_page'));
    }

    protected function updatePartial(Event $event, Request $request): void
    {
        foreach ($request->only('status') as $key => $content) {
            if ($event->isTranslatableAttribute($key)) {
                foreach ($content as $lang => $value) {
                    $event->setTranslation($key, $lang, $value);
                }
            } else {
                $event->{$key} = $content;
            }
        }

        $event->save();
    }

    public function duplicate(Event $event): void
    {
        $newEvent = $event->replicate();
        $newEvent->setTranslations('status', []);
        $newEvent->save();
    }

    public function destroy(Event $event): JsonResponse
    {
        $event->delete();

        return response()->json(status: 204);
    }
}
