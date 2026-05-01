<x-core::layouts.page :page="$page" :body-class="'body-events body-events-index body-page body-page-' . $page->id">
    <x-slot:page-header>
        <header class="page-header">
            <div class="page-header-container">
                <h1>@lang('Past events')</h1>
            </div>
        </header>
    </x-slot:page-header>

    <div class="page-body">
        <div class="page-body-container">
            @includeWhen($models->count() > 0, 'public::events._list', ['items' => $models])

            {!! $models->appends(Request::except('page'))->links() !!}

            <div class="text-center">
                <a href="{{ route(app()->getLocale() . '::index-events') }}" class="btn btn-light">@lang('Upcoming events')</a>
            </div>
        </div>
    </div>
</x-core::layouts.page>
