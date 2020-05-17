<?php

namespace Tests;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Page extends Model
{
    use AttributeMulticasting, ReadwriteInstance;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'priority',
        'title',
        'subtitle',
        'content',
        'description',
        'description_tag',
        'keywords_tag',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'published_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'important' => 'boolean',
    ];

    /**
     * Returns a parent of the page.
     *
     * @return HasOne
     */
    public function parent(): HasOne
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    /**
     * Returns children of the page.
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Returns a template of the page.
     *
     * @return BelongsTo
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Returns additional fields of the page.
     *
     * @return HasMany
     */
    public function additionalFields(): BelongsToMany
    {
        return $this->belongsToMany(AdditionalField::class)
            ->using(AdditionalFieldsOfPages::class)
            ->withPivot('values')
        ;
    }

    /**
     * The alias of the additionalFields function.
     *
     * @return HasMany
     */
    public function afs(): BelongsToMany
    {
        return $this->additionalFields();
    }

    /**
     * Returns pages that have the given state.
     *
     * @param  Builder $query
     * @param  string  $state
     * @return Builder
     */
    public function scopeState(Builder $query, string $state): Builder
    {
        return $query->where('state', $state);
    }

    /**
     * Returns pages that have the given slug.
     *
     * @param  Builder $query
     * @param  string  $slug
     * @return Builder
     */
    public function scopeSlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', 'like', $slug);
    }

    /**
     * Returns pages that have the given link.
     *
     * @param  Builder $query
     * @param  string  $link
     * @return Builder
     */
    public function scopeLink(Builder $query, string $link): Builder
    {
        return $query->where('link', 'like', $link);
    }

    /**
     * Returns active pages.
     * An active page is a page whose the state is PUBLISHED.
     * Set 'false' to $active to get inactive pages.
     *
     * @param  Builder $query
     * @param  bool    $active
     * @return Builder
     */
    public function scopeActive(Builder $query, bool $active = true): Builder
    {
        return $active
            ? $query->state(PageState::PUBLISHED)
            : $query->where('state', '<>', PageState::PUBLISHED)
        ;
    }

    /**
     * Returns pages that have active templates.
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeActiveTemplate(Builder $query): Builder
    {
        return $query->whereHas('template', function ($query) {
            $query->active();
        });
    }

    /**
     * Returns published pages.
     * A published page is a page whose a date of publication greater or equal
     * than the current date.
     *
     * WARNING: The NOW() function do not work with SQLite.
     * Set $currentDate with an instance of DateTime, such as "new DateTime('now')".
     *
     * @param  Builder  $query
     * @param  DateTime $currentDate
     * @return Builder
     */
    public function scopePublished(Builder $query, DateTime $currentDate = null): Builder
    {
        return $currentDate
            ? $query->whereRaw('published_at <= ?', $currentDate->format('Y-m-d H:i:s'))
            : $query->whereRaw('published_at <= NOW()')
        ;
    }

    /**
     * Returns pages that are allowed to show.
     *
     * @param  DateTime $currentDate
     * @return Builder
     */
    public function scopeSeen(Builder $query, DateTime $currentDate = null): Builder
    {
        return $query
            ->activeTemplate()
            ->active()
            ->published($currentDate)
        ;
    }

    public function newCollection(array $models = [])
    {
        return new PageCollection($models);
    }
}
