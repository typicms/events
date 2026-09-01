@use('TypiCMS\Modules\Core\Support\ModuleUrl')
<x-mail::message>
    # {{ __('New registration to an event') }}

    {{ __('A new registration to') }}
    [{{ $event->title }}]({{ ModuleUrl::item('events', $event->slug) }})
    {{ __('was requested by') }}
    {{ $registration->title }} {{ $registration->first_name }} {{ $registration->last_name }}.

    <x-mail::table>
        | | | | ------------------------------ | ------------------ | | **
        {{ __('Event') }}
        ** | [{{ $event->title }}]({{ ModuleUrl::item('events', $event->slug) }}) | **
        {{ __('Name') }}
        ** | {{ $registration->first_name }} {{ $registration->last_name }} | **
        {{ __('Email') }}
        ** | [{{ $registration->email }}](mailto:{{ $registration->email }}) | **
        {{ __('Number of people') }}
        ** | {{ $registration->number_of_people }} | **
        {{ __('Message') }}
        ** | {{ $registration->message }}
    </x-mail::table>

    <x-mail::button :url="route('admin::edit-registration', [$event->id, $registration->id])">
        {{ __('See online') }}
    </x-mail::button>>

    {{ config('app.name') }}
</x-mail::button>
