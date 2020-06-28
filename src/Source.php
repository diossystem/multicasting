<?php

namespace Dios\System\Multicasting;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

/**
 * It used for keeping sources of an entity key.
 */
class Source
{
    /**
     * A type is kept in the current model.
     */
    const CURRENT_MODEL_TYPE = 0;

    /**
     * A type is kept in another model.
     */
    const ANOTHER_MODEL_TYPE = 1;

    /**
     * An original source;
     *
     * @var string
     */
    protected $source;

    /**
     * A basic model that contains keys.
     *
     * @var Model
     */
    protected $model;

    /**
     * A current source type.
     *
     * @var int
     */
    protected $type;

    /**
     * A real source.
     *
     * @var string
     */
    protected $realSource;

    /**
     * Segments of a real source.
     *
     * @var array|string[]|int[]
     */
    protected $segmentsOfRealSource;

    /**
     * A source of key.
     *
     * @var string
     */
    protected $sourceOfKey;

    /**
     * Initializes an instance with a source of the entity and a basic model
     * that contains keys.
     *
     * @param string $source
     * @param Model  $model
     */
    public function __construct(string $source, $model)
    {
        $this->source = $source;
        $this->model = $model;
        $this->type = $this->defineSources($source);
    }

    /**
     * Returns the current source type.
     *
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Returns the real source where type keeps.
     *
     * @return string
     */
    public function getRealSource()
    {
        return $this->realSource;
    }

    /**
     * Returns segments of a real source.
     *
     * @return array|string[]
     */
    public function getSegmentsOfRealSource(): array
    {
        return $this->segmentsOfRealSource;
    }

    /**
     * Returns the source of an entity key.
     *
     * @return string
     */
    public function getSourceOfKey(): string
    {
        return $this->sourceOfKey;
    }

    /**
     * Returns an object of the source.
     *
     * @return mixed|Model|null
     */
    public function getObjectOfSource()
    {
        $relatedModel = $this->model;

        foreach ($this->segmentsOfRealSource as $segment) {
            if (method_exists($relatedModel, $segment)) {
                $relation = $relatedModel->$segment();

                if (! ($relation instanceof Relation)) {
                    return null;
                }

                $relatedModel = $relation->getRelated();
            }
        }

        return $relatedModel;
    }

    public function getEntityTypeByCurrentKey()
    {
        /** @var int|string|null $key */
        $key = $this->model->{$this->getSourceOfKey()};

        return $this->getEntityTypeByKey($key);
    }

    /**
     * Returns an entity type by the given key.
     *
     * @param  string|int|null $key
     * @return string|int|null
     */
    public function getEntityTypeByKey($key)
    {
        /** @var Model|null */
        $object = $this->getObjectOfSource();
        /** @var string|bool $property */
        $property = end($this->segmentsOfRealSource);

        if ($key === null || $object === null || $property === false) {
            return null;
        }

        $modelWithValue = count($this->segmentsOfRealSource) === 1
            ? $object->where($property, $key)->first()
            : $object::find($key);

        return $modelWithValue ? $modelWithValue->$property : null;
    }

    /**
     * Returns entity types with keys.
     * Keys of the array are keys (indexes) of the types.
     *
     * @param  bool $groupByProperty Grouping same types.
     * @return array|string[]|int[]
     */
    public function getEntityTypes(bool $groupByProperty = true)
    {
        /** @var Model|null */
        $object = $this->getObjectOfSource();
        /** @var string|bool $property */
        $property = end($this->segmentsOfRealSource);

        if ($object === null || $property === false) {
            return null;
        }

        /** @var Builder $query */
        $query = $groupByProperty ? $object->groupBy($property) : $object;

        /** @var Collection|string[] $collectionWithTypes */
        $collectionWithTypes = count($this->segmentsOfRealSource) === 1
            ? $query->pluck($property, $property)
            : $query->pluck($property, $object->getKeyName())
        ;

        $types = $collectionWithTypes->toArray();
        $types = $this->deleteNullKeys($types);
        ksort($types);

        return $types;
    }

    /**
     * Detectes a source type from the given string.
     *
     * @param  string $source
     * @return int
     */
    public static function detectType(string $source): int
    {
        $sources = explode('|', $source, 2);

        return count($sources) === 2
            ? self::ANOTHER_MODEL_TYPE
            : self::CURRENT_MODEL_TYPE
        ;
    }

    /**
     * Defines the sources and returns a detected source type.
     *
     * @param  string $source
     * @return int
     */
    protected function defineSources(string $source): int
    {
        $sources = explode('|', $source, 2);
        $type = self::CURRENT_MODEL_TYPE;

        if (count($sources) === 2) {
            list($realSource, $this->sourceOfKey) = $sources;
            $this->defineRealSource($realSource);
            $type = self::ANOTHER_MODEL_TYPE;
        } else {
            $this->defineRealSource($sources[0]);
            $this->sourceOfKey = $sources[0];
        }

        return $type;
    }

    /**
     * Defines a real source and its segments.
     *
     * @param  string $source
     * @return void
     */
    protected function defineRealSource(string $source)
    {
        $this->realSource = $source;
        $this->segmentsOfRealSource = explode('.', $source);
    }

    /**
     * Returns an array without null keys.
     *
     * @param  array $array
     * @return array
     */
    protected function deleteNullKeys(array $array): array
    {
        return array_filter($array, function ($key) {
            return ! empty($key);
        }, ARRAY_FILTER_USE_KEY);
    }
}
