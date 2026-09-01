<?php

declare(strict_types=1);

namespace TypiCMS\Modules\Events\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;
use Spatie\ResponseCache\Attributes\NoCache;
use TypiCMS\Modules\Core\Http\Controllers\BasePublicController;
use TypiCMS\Modules\Core\Models\User;
use TypiCMS\Modules\Core\Support\ModuleUrl;
use TypiCMS\Modules\Events\Http\Requests\RegistrationFormRequest;
use TypiCMS\Modules\Events\Models\Event;
use TypiCMS\Modules\Events\Models\Registration;
use TypiCMS\Modules\Events\Notifications\NewRegistrationToAnEvent;
use TypiCMS\Modules\Events\Notifications\RegisteredToEvent;
use TypiCMS\Modules\Events\Services\Calendar;

final class PublicController extends BasePublicController
{
    public function __construct(
        private readonly Calendar $calendar,
    ) {}

    public function index(): View
    {
        $models = Event::query()
            ->published()
            ->with('image')
            ->orderBy('start_date')
            ->where('end_date', '>=', date('Y-m-d'))
            ->paginate(config('typicms.modules.events.per_page'));

        return view('public::events.index', ['models' => $models]);
    }

    public function past(): View
    {
        $models = Event::query()
            ->published()
            ->with('image')
            ->orderBy('end_date', 'desc')
            ->where('end_date', '<', date('Y-m-d'))
            ->paginate(config('typicms.modules.events.per_page'));

        return view('public::events.past', ['models' => $models]);
    }

    public function show(string $slug): View
    {
        $model = Event::query()
            ->published()
            ->with([
                'image',
                'images',
                'documents',
            ])
            ->whereSlugIs($slug)
            ->firstOrFail();

        return view('public::events.show', ['model' => $model]);
    }

    #[NoCache]
    public function showRegistrationForm(string $slug): View
    {
        $event = Event::query()
            ->published()
            ->whereSlugIs($slug)
            ->firstOrFail();
        abort_if(! $event->registration_form || $event->end_date < date('Y-m-d'), 404);

        return view('public::events.registration', ['event' => $event]);
    }

    public function register(string $slug, RegistrationFormRequest $request): RedirectResponse
    {
        $event = Event::query()
            ->published()
            ->whereSlugIs($slug)
            ->firstOrFail();
        abort_if(! $event->registration_form || $event->end_date < date('Y-m-d'), 404);
        /** @var User $user */
        $user = auth()->user();
        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['event_id'] = $event->id;
        $data['first_name'] = $user->first_name;
        $data['last_name'] = $user->last_name;
        $data['email'] = $user->email;
        $data['locale'] = $user->locale;

        $registration = Registration::query()->create($data);

        $recipients = User::query()->permission('receive event registration notifications')->get();

        Notification::send($recipients, new NewRegistrationToAnEvent($event, $registration));

        Notification::route('mail', $data['email'])->notify(new RegisteredToEvent($event, $registration));

        return redirect()
            ->to(ModuleUrl::to('events', [$event->slug, 'registered']) ?? '/')
            ->with('success', true);
    }

    #[NoCache]
    public function registered(string $slug): RedirectResponse|View
    {
        $event = Event::query()
            ->published()
            ->whereSlugIs($slug)
            ->firstOrFail();
        if (session('success')) {
            return view('public::events.registered', ['event' => $event]);
        }

        return redirect(url('/'));
    }

    public function ics(string $slug): Response
    {
        $event = Event::query()
            ->published()
            ->whereSlugIs($slug)
            ->firstOrFail();

        $this->calendar->add($event);

        $response = response($this->calendar->render(), 200);
        $response->header('Content-Type', 'text/calendar; charset=utf-8');
        $response->header('Content-Disposition', 'attachment; filename="'.$event->slug.'.ics"');

        return $response;
    }
}
