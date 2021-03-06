<?php

namespace Tests\Models;

use Dios\System\Multicasting\AttributeMulticasting;
use Dios\System\Multicasting\ReadwriteInstance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{
    use AttributeMulticasting, ReadwriteInstance;

    const SINGLE_TYPE = 'single';

    const ROLL_PAPER_TYPE = 'roll';

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
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * The instance type of entities.
     *
     * @var string
     */
    protected $interfaceType = \Dios\System\Multicasting\Interfaces\EntityWithModel::class;

    /**
     * The source that contains an entity type.
     *
     * @var string
     */
    protected $sourceWithEntityType = 'type';

    /**
     * Type mapping of entity types and their handlers.
     *
     * @var array
     */
    protected $entityTypeMapping = [
        self::SINGLE_TYPE => \Tests\Models\SheetTypes\SingleType::class,
        self::ROLL_PAPER_TYPE => \Tests\Models\SheetTypes\RollPaperType::class,
    ];

    /**
     * The state of configuring instances of entities.
     *
     * @var bool
     */
    // protected $configureInstanceOfEntity = true;

    /**
     * The state of filling instances of entities.
     *
     * @var bool
     */
    protected $fillInstance = false;

    /**
     * Returns sheets with the given type.
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
     * Returns supported types.
     *
     * @return array|string[]
     */
    public static function getTypes(): array
    {
        return [self::SINGLE_TYPE, self::ROLL_PAPER_TYPE];
    }
}
