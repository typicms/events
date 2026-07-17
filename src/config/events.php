<?php

declare(strict_types=1);

use TypiCMS\Modules\Events\Models\Event;

return [
    'model' => Event::class,
    'linkable_to_page' => true,
    'llms_txt' => true,
    'per_page' => 50,
    'order' => [
        'end_date' => 'desc',
    ],
    'sidebar' => [
        'icon' => '<i class="icon-calendar-range"></i>',
        'weight' => 50,
    ],
    'permissions' => [
        'read events' => 'Read',
        'create events' => 'Create',
        'update events' => 'Update',
        'delete events' => 'Delete',
        'receive event registration notifications' => 'Receive registration notifications',
        'read registrations' => 'Read registrations',
        'update registrations' => 'Update registrations',
        'delete registrations' => 'Delete registrations',
    ],
];
