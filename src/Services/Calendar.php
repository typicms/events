<?php

declare(strict_types=1);

namespace TypiCMS\Modules\Events\Services;

use Eluceo\iCal\Domain\Entity\Calendar as ICalendar;
use Eluceo\iCal\Domain\Entity\Event as IEvent;
use Eluceo\iCal\Domain\Entity\TimeZone;
use Eluceo\iCal\Domain\ValueObject\Date as iCalDate;
use Eluceo\iCal\Domain\ValueObject\DateTime as iCalDateTime;
use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\MultiDay;
use Eluceo\iCal\Domain\ValueObject\SingleDay;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Presentation\Component;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Illuminate\Support\Facades\Date;
use TypiCMS\Modules\Events\Models\Event;

class Calendar
{
    public function __construct(
        protected ICalendar $iCalendar,
    ) {}

    public function add(Event $model): void
    {
        if ($model->start_time && $model->end_time) {
            $startDate = Date::createFromFormat('Y-m-d H:i:s', $model->start_date.' '.$model->start_time.':00');
            $endDate = Date::createFromFormat('Y-m-d H:i:s', $model->end_date.' '.$model->end_time.':00');

            if (! $startDate || ! $endDate) {
                return;
            }

            $start = new iCalDateTime($startDate, false);
            $end = new iCalDateTime($endDate, false);
            $occurrence = new TimeSpan($start, $end);
        } elseif ($model->start_date === $model->end_date) {
            $date = new iCalDate(Date::createFromFormat('Y-m-d', $model->start_date));
            $occurrence = new SingleDay($date);
        } else {
            $firstDay = new iCalDate($model->start_date);
            $lastDay = new iCalDate($model->end_date);
            $occurrence = new MultiDay($firstDay, $lastDay);
        }

        // fill event
        $iEvent = new IEvent;
        $iEvent
            ->setOccurrence($occurrence)
            ->setSummary($model->title ?? '')
            ->setDescription($model->summary ?? '');

        if ($model->address !== null) {
            $iEvent->setLocation(new Location($model->address, $model->venue));
        }
        // add it to the calendar
        $this->iCalendar->addEvent($iEvent);
        $this->iCalendar->addTimeZone(new TimeZone(config('app.timezone')));
    }

    public function render(): Component
    {
        return new CalendarFactory()->createCalendar($this->iCalendar);
    }
}
