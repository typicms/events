<x-core::header :$model :back-url="route('admin::index-registrations', $event->id)" :back-label="__('Registrations')" :lang-switcher="false" />

<div class="form-body">
    <x-core::form-errors />

    {!! BootForm::text(__('Event'), 'event')->value($model->event->title)->readOnly() !!} {!! Bootform::number(__('Number of people'), 'number_of_people')->min(1)->required() !!} {!! Bootform::textarea(__('Message'), 'message')->rows(3) !!}
</div>
