@use('TypiCMS\Modules\Core\Support\ModuleUrl')
<x-mail::message>
    # {{ __('Dear'), }}

    {{ __('Thank you for your registration to') }}
    “[{{ $event->title }}]({{ ModuleUrl::item('events', $event->slug) }})”.

    <x-mail::table>
        | | | | ----------------------------- | ------------------ | | **
        {{ __('Name') }}
        ** | {{ $registration->first_name }} {{ $registration->last_name }} | **
        {{ __('Number of people') }}
        ** | {{ $registration->number_of_people }} | **
        {{ __('Message') }}
        ** | {{ $registration->message }}
        </x-mail::table>

    {{ __('If you have any questions leading up to the event, feel free to contact us.') }}

    {{ __('We look forward to seeing you on:') }}
    <x-core::date-range :start="$event->start_date" :end="$event->end_date" />.

    {{ config('app.name') }}
</x-mail::message>
