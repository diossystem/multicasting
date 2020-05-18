<?php

namespace Tests\Models;

use DateTime;
use Dios\System\Multicasting\AttributeMulticasting;
use Dios\System\Multicasting\ReadwriteInstance;
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
        'title',
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
}
