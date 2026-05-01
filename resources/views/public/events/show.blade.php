<x-core::layouts.public
    :title="$model->title . ' – ' . __('Events') . ' – ' . websiteTitle()"
    :og-title="$model->title ?? ''"
    :description="$model->summary ?? ''"
    :og-image="$model->ogImageUrl()"
    :body-class="'body-events body-event-' . $model->id . ' body-page body-page-' . $page->id"
    :page="$page"
    :model="$model"
>
    <article class="event container-xl">
        <header class="event-header">
            <div class="event-header-container">
                <div class="event-header-navigator">
                    <x-core::items-navigator :$model :$page />
                </div>
                <h1 class="event-title">{{ $model->title }}</h1>
                <div class="event-date"><x-core::date-range :start="$model->start_date" :end="$model->end_date" /></div>
                <a class="btn btn-light btn-xs" href="{{ route(app()->getLocale() . '::event-ics', $model->slug) }}">
                    @lang('Add to calendar')
                </a>
                <div class="event-location">
                    <span class="event-venue">{{ $model->venue }}</span>
                    <div class="event-address">{!! nl2br($model->address) !!}</div>
                </div>
                @if ($model->website)
                    <div class="event-url">
                        <a href="{{ $model->website }}" target="_blank" rel="noopener noreferrer">{{ parse_url($model->website, PHP_URL_HOST) }}</a>
                    </div>
                @endif

                @if ($model->registration_form && $model->end_date >= date('Y-m-d'))
                    <div class="event-register">
                        <a
                            class="btn btn-sm btn-success"
                            href="{{ Route::has(app()->getLocale() . '::event-registration') ? route(app()->getLocale() . '::event-registration', ['slug' => $model->slug]) : '/' }}"
                        >
                            @lang('Register')
                        </a>
                    </div>
                @endif
            </div>
        </header>
        <div class="event-body">
            <x-core::json-ld
                :schema="[
                '@context' => 'https://schema.org',
                '@type' => 'Event',
                'name' => $model->title,
                'startDate' => $model->start_date->format('c'),
                'endDate' => $model->end_date->format('c'),
                'description' => $model->summary ? $model->summary : Str::limit(strip_tags($model->body), 200),
                'image' => [$model->image?->render()],
                'location' => [
                    '@type' => 'Place',
                    'name' => $model->venue,
                    'address' => $model->address,
                ],
                ...($model->website ? ['url' => $model->website] : []),
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => $model->url(),
                ],
            ]"
            />
            @if ($model->summary)
                <p class="event-summary">{!! nl2br($model->summary) !!}</p>
            @endif

            <x-core::share-links :$model />
            @if ($model->image)
                <figure class="event-picture">
                    <img class="event-picture-image" src="{{ $model->image->render(2000) }}" width="{{ $model->image->width / 5 }}" height="{{ $model->image->height / 5 }}" alt="" />
                    @if ($model->image->description)
                        <figcaption class="event-picture-legend">{{ $model->image->description }}</figcaption>
                    @endif
                </figure>
            @endif

            @if ($model->body)
                <div class="rich-content">{!! $model->formattedBody() !!}</div>
            @endif

            @include('public::files._document-list')
            @include('public::files._image-list')
        </div>
    </article>
</x-core::layouts.public>
