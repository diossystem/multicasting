<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdditionalField extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
    ];

    /**
     * Returns pages of the additional field.
     *
     * @return BelongsToMany
     */
    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class)
            ->using(AdditionalFieldsOfPages::class)
            ->withPivot('values')
        ;
    }

    /**
     * Returns additional fields that have the given names.
     *
     * @param  Builder $query
     * @param  string  ...$name
     * @return Builder
     */
    public function scopeName(Builder $query, string ...$name): Builder
    {
        return $query->whereIn('code_name', $name);
    }

    /**
     * Returns additional fields with a given type.
     *
     * @param  Builder $query
     * @param  string  $type
     * @return Builder
     */
    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Returns additional field that have the active state or another given state.
     *
     * @param  Builder $query
     * @param  bool    $state
     * @return Builder
     */
    public function scopeActive(Builder $query, bool $state = true): Builder
    {
        return $query->where('active', $state);
    }
}
