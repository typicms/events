<?php

declare(strict_types=1);

namespace TypiCMS\Modules\Events\Models;

use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Uri;
use Override;
use TypiCMS\Modules\Core\Models\File;
use TypiCMS\Modules\Core\Models\History;
use TypiCMS\Modules\Core\Observers\SlugObserver;
use TypiCMS\Modules\Core\Observers\TipTapHTMLObserver;
use TypiCMS\Modules\Core\Support\ModuleUrl;
use TypiCMS\Modules\Core\Traits\HasAdminUrls;
use TypiCMS\Modules\Core\Traits\HasBodyPresenter;
use TypiCMS\Modules\Core\Traits\HasConfigurableOrder;
use TypiCMS\Modules\Core\Traits\HasContentPresenter;
use TypiCMS\Modules\Core\Traits\HasFiles;
use TypiCMS\Modules\Core\Traits\HasOgImage;
use TypiCMS\Modules\Core\Traits\HasSelectableFields;
use TypiCMS\Modules\Core\Traits\HasSlugScope;
use TypiCMS\Modules\Core\Traits\Historable;
use TypiCMS\Modules\Core\Traits\Navigable;
use TypiCMS\Modules\Core\Traits\Publishable;
use TypiCMS\Translatable\HasTranslations;

/**
 * @property int $id
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property string|null $start_time
 * @property string|null $end_time
 * @property int $registration_form
 * @property int|null $og_image_id
 * @property int|null $image_id
 * @property string $status
 * @property string $title
 * @property string $slug
 * @property ?string $venue
 * @property ?string $address
 * @property string $summary
 * @property string $body
 * @property string $website
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, File> $audios
 * @property-read Collection<int, File> $documents
 * @property-read Collection<int, File> $files
 * @property-read Collection<int, History> $history
 * @property-read File|null $image
 * @property-read Collection<int, File> $images
 * @property-read File|null $ogImage
 * @property-read Collection<int, Registration> $registrations
 * @property-read mixed $thumb
 * @property-read mixed $translations
 * @property-read Collection<int, File> $videos
 */
#[ObservedBy([SlugObserver::class, TipTapHTMLObserver::class])]
#[Unguarded]
#[Appends(['thumb'])]
class Event extends Model
{
    use HasAdminUrls;
    use HasBodyPresenter;
    use HasConfigurableOrder;
    use HasContentPresenter;
    use HasFiles;
    use HasOgImage;
    use HasSelectableFields;
    use HasSlugScope;
    use HasTranslations;
    use Historable;
    use Navigable;
    use Publishable;

    /** @return array<string, string> */
    #[Override]
    protected function casts(): array
    {
        return [
            'start_date' => 'datetime:Y-m-d',
            'end_date' => 'datetime:Y-m-d',
        ];
    }

    /** @var array<string> */
    public array $translatable = [
        'title',
        'slug',
        'status',
        'venue',
        'address',
        'summary',
        'body',
        'website',
    ];

    /** @var array<string> */
    public array $tipTapContent = [
        'body',
    ];

    public function url(?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        return ModuleUrl::item('events', $this->translate('slug', $locale), $locale);
    }

    public function previewUrl(?string $locale = null): ?string
    {
        $url = $this->url($locale);

        if (! $url) {
            return null;
        }

        return (string) Uri::of($url)->withQuery(['preview' => 'true']);
    }

    /** @return Collection<int, static> */
    public function upcoming(?int $number = null): Collection
    {
        $query = static::query()
            ->published()
            ->where('end_date', '>=', date('Y-m-d'))
            ->orderBy('start_date');
        if ($number !== null && $number !== 0) {
            $query->take($number);
        }

        return $query->get();
    }

    /** @return Collection<int, static> */
    public function past(?int $number = null): Collection
    {
        $query = static::query()
            ->published()
            ->where('end_date', '<', date('Y-m-d'))
            ->order();
        if ($number !== null && $number !== 0) {
            $query->take($number);
        }

        return $query->get();
    }

    public function adjacent(int $direction, ?int $category_id = null): ?Model
    {
        $collection = $this->end_date->lt(today()) ? $this->past() : $this->upcoming();

        $key = $collection->search(fn (self $event): bool => $event->id === $this->id);

        if ($key === false) {
            return null;
        }

        return $collection[$key + $direction] ?? null;
    }

    /** @return Attribute<string, null> */
    protected function thumb(): Attribute
    {
        return Attribute::make(get: fn (): string => imageOrDefault($this->image, null, 54));
    }

    /** @return HasMany<Registration, $this> */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /** @return BelongsTo<File, $this> */
    public function image(): BelongsTo
    {
        return $this->belongsTo(File::class, 'image_id');
    }

    /** @return BelongsTo<File, $this> */
    public function ogImage(): BelongsTo
    {
        return $this->belongsTo(File::class, 'og_image_id');
    }
}
