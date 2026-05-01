<x-core::layouts.admin :title="$model->presentTitle()" :model="$model">
    {!! BootForm::open()->put()->action(route('admin::update-event', $model->id))->addClass('form') !!} {!! BootForm::bind($model) !!}
    @include('admin::events._form')
    {!! BootForm::close() !!}
</x-core::layouts.admin>
