<x-core::layouts.page :page="$page" :body-class="'body-events body-events-index body-page body-page-' . $page->id">
    <x-core::json-ld
        :schema="[
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'itemListElement' => $models->map(fn ($item, $index) => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'url' => $item->url(),
        ])->all(),
    ]"
    />
    <div class="page-body">
        <div class="page-body-container">
            @include('public::pages._main-content', ['page' => $page])
            @include('public::files._document-list', ['model' => $page])
            @include('public::files._image-list', ['model' => $page])

            @includeWhen($models->count() > 0, 'public::events._list', ['items' => $models])

            {!! $models->appends(Request::except('page'))->links() !!}

            <div class="text-center">
                <a href="{{ route(app()->getLocale() . '::past-events') }}" class="btn btn-light">@lang('Past events')</a>
            </div>
        </div>
    </div>
</x-core::layouts.page>
