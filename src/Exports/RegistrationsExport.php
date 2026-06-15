<?php

declare(strict_types=1);

namespace TypiCMS\Modules\Events\Exports;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use TypiCMS\Modules\Core\Exports\EscapesFormulas;
use TypiCMS\Modules\Events\Filters\FilterRegistrations;
use TypiCMS\Modules\Events\Models\Event;
use TypiCMS\Modules\Events\Models\Registration;

/**
 * @implements WithMapping<mixed>
 */
class RegistrationsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStrictNullComparison
{
    use EscapesFormulas;

    /** @var Collection<int, Registration> */
    protected Collection $collection;

    public function __construct(Request $request)
    {
        $query = Registration::query()
            ->selectFields()
            ->where('event_id', $request->route()?->originalParameter('event'))
            ->addSelect([
                'event_name' => Event::query()
                    ->select(column('title'))
                    ->whereColumn('event_id', 'events.id')
                    ->limit(1),
            ]);
        $this->collection = QueryBuilder::for($query)
            ->allowedSorts('created_at', 'first_name', 'last_name', 'email', 'locale', 'number_of_people', 'message')
            ->allowedFilters(
                AllowedFilter::custom(
                    'created_at,first_name,last_name,email,locale,number_of_people,message',
                    new FilterRegistrations,
                ),
            )
            ->get();
    }

    /** @return array<int, mixed> */
    public function map(mixed $row): array
    {
        return [
            $row->created_at,
            $this->escapeFormula($row->event_name),
            $row->number_of_people,
            $this->escapeFormula($row->first_name),
            $this->escapeFormula($row->last_name),
            $this->escapeFormula($row->email),
            $row->locale,
            $this->escapeFormula($row->message),
        ];
    }

    /** @return string[] */
    public function headings(): array
    {
        return [
            __('Date'),
            __('Event'),
            __('Number of people'),
            __('First name'),
            __('Last name'),
            __('Email'),
            __('Language'),
            __('Message'),
        ];
    }

    /** @return Collection<int, Registration> */
    public function collection(): Collection
    {
        return $this->collection;
    }
}
