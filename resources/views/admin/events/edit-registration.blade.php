<x-core::layouts.admin :title="$model->presentTitle()" :model="$model">
    {!! BootForm::open()->put()->action(route('admin::update-registration', [$event->id, $model->id]))->addClass('form') !!} {!! BootForm::bind($model) !!}
    @include('admin::events._form-registration')
    {!! BootForm::close() !!}
</x-core::layouts.admin>
