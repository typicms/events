<x-core::header
    :$model
    :back-url="route('admin::index-registrations', $event->id)"
    :back-label="__('Registrations')"
    :lang-switcher="false"
/>

<div class="form-body">
    <x-core::form-errors />

    <x-bootform::text :label="__('Event')" name="event" :value="$model->event->title" readonly />

    <x-bootform::number :label="__('Number of people')" name="number_of_people" min="1" required />

    <x-bootform::textarea :label="__('Message')" name="message" rows="3" />
</div>
