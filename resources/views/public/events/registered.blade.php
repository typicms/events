<x-core::layouts.page :page="$page" :body-class="'body-registrations body-registration-sent body-page body-page-' . $page->id">
    <article class="event">
        <header class="event-header">
            <div class="event-header-container">
                <div class="event-header-navigator">
                    <div class="items-navigator">
                        <a class="items-navigator-back" href="{{ $event->url() }}">
                            ←
                            @lang('Back')
                        </a>
                    </div>
                </div>
                <h1 class="event-title">{{ $event->title }}</h1>
                <div class="event-date">
                    <x-core::date-range :start="$event->start_date" :end="$event->end_date" />
                    <a class="event-add-to-calendar" href="{{ route(app()->getLocale() . '::event-ics', $event->slug) }}">
                        <span class="visually-hidden">@lang('Add to calendar')</span>
                        <img class="ms-2" src="/img/calendar-add.svg" alt="" />
                    </a>
                </div>
                <div class="event-location">
                    <span class="event-venue">{{ $event->venue }}</span>
                    <div class="event-address">{!! nl2br($event->address) !!}</div>
                </div>
            </div>
        </header>

        <div class="event-body">
            <p class="alert alert-success">@lang('event registration message')</p>
            <a class="btn btn-sm btn-primary" href="{{ $event->url() }}">
                ←
                @lang('Back')
            </a>
        </div>
    </article>
</x-core::layouts.page>
