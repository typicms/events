<x-core::layouts.page :page="$page" :body-class="'body-registrations body-registrations-form body-page body-page-' . $page->id">
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
            <h2 class="text-danger">@lang('Registration')</h2>
            @if (!$errors->isEmpty())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="@lang('Close')"></button>
                    @lang('message on form error')
                    <ul class="mb-0">
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {!! BootForm::open()->action(route(app()->getLocale() . '::event-register', $event->slug)) !!}
            <div class="row">
                <div class="col-sm-6 col-lg-3">{!! BootForm::number(__('Number of people'), 'number_of_people')->min(1)->value(1)->required() !!}</div>
            </div>
            {!! BootForm::textarea(__('Comment'), 'message')->rows(3) !!}
            <button class="btn btn-primary" type="submit">{{ __('Register') }}</button>
            {!! BootForm::close() !!}
        </div>
    </article>
</x-core::layouts.page>
