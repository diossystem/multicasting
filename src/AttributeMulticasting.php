<?php

namespace Dios\System\Multicasting;

/**
 * The trait handlers models that have only one attribute
 * that can body forth many possible entities.
 */
trait AttributeMulticasting
{
    /**
     * The type name is 'instance from model'.
     *
     * @var string
     */
    const INSTANCE_FROM_MODEL = 'instance_from_model';

    /**
     * THe type name is 'related_entity'.
     *
     * @var string
     */
    const RELATED_ENTITY = 'related_entity';

    /**
     * The type name is 'simple'.
     *
     * @var string
     */
    const SIMPLE = 'simple';

    /**
     * The cache of entity keys.
     *
     * @var array
     */
    protected static $entityKeyCache = [];

    /**
     * An instance of the current entity.
     *
     * @var EntityHandlerInterface|null
     */
    protected $instanceOfEntity;

    /**
     * Returns an entity type.
     *
     * @param  bool   $cache
     * @return string|mixed|null
     */
    public function getEntityType(bool $cache = true)
    {
        /** @var array $sources **/
        $sources = explode('|', $this->sourceWithEntityType, 2);

        if (count($sources) === 2) {
            list($realSource, $linkToSource) = $sources;

            // Gets a key from the static cache
            if ($cache && self::hasCacheEntityKey($linkToSource)) {
                return self::getCacheOfEntityKey($linkToSource);
            }

            /** @var string|mixed|null $key **/
            $key = $this->getEntityKey($realSource);

            // Caches using $linkToSource
            if ($cache) {
                self::addCacheOfEntityKey($linkToSource, $key);
            }

            return $key;
        }

        return isset($sources[0])
            ? $this->getEntityKey($sources[0])
            : null
        ;
    }

    /**
     * Returns an entity key from its source.
     *
     * @param  string $source
     * @return mixed|null
     */
    protected function getEntityKey(string $source)
    {
        /** @var array $segments Segments to a value ***/
        $segments = explode('.', $source);

        // Uses the current model as the start value
        /** @var Model|string|null **/
        $value = $this;

        // Finds a key using all segments. They may be in related models.
        foreach ($segments as $segment) {
            if (isset($value->$segment)) {
                $value = $value->$segment;
            } else {
                $value = null;
                break;
            }
        }

        return is_scalar($value)
            ? $value
            : null
        ;
    }

    /**
     * Adds a new value of cache of entity keys.
     *
     * @param string|mixed $key
     * @param string|mixed|null $value
     */
    protected static function addCacheOfEntityKey($key, $value)
    {
        self::$entityKeyCache[$key] = $value;
    }

    /**
     * Returns an entity key by its key index.
     *
     * @param  string|mixed $key
     * @return string|mixed|null
     */
    public static function getCacheOfEntityKey($key)
    {
        return self::$entityKeyCache[$key] ?? null;
    }

    /**
     * Returns true when the entity key is cached.
     *
     * @param  string|mixed $key
     * @return bool
     */
    public static function hasCacheEntityKey($key): bool
    {
        return key_exists($key, self::$entityKeyCache);
    }

    /**
     * Returns the current cache of entity keys.
     *
     * @return array
     */
    public static function getCacheOfEntityKeys(): array
    {
        return self::$entityKeyCache;
    }

    /**
     * Returns true when the mapping has the entity type.
     *
     * @param  string|mixed $type
     * @return bool
     */
    public function hasEntityType($type): bool
    {
        return isset($this->entityTypeMapping[$type]) && class_exists($this->entityTypeMapping[$type]);
    }

    /**
     * Returns true when the default entity handler exists.
     *
     * @return bool
     */
    public function hasDefaultEntityHandler(): bool
    {
        return is_string($this->defaultEntityHandler) && class_exists($this->defaultEntityHandler);
    }

    /**
     * Returns the default entity handler class name.
     *
     * @return string|null
     */
    public function getDefaultEntityHandlerClassName()
    {
        return $this->hasDefaultEntityHandler()
            ? $this->defaultEntityHandler
            : null;
        ;
    }

    /**
     * Returns an entity handler class name by its type.
     *
     * @param  string $type
     * @return string|null
     */
    public function getEntityHandlerClassNameByType($type)
    {
        return $this->hasEntityType($type)
            ? $this->entityTypeMapping[$type]
            : null
        ;
    }

    /**
     * Returns a class name of an entity handler by the type.
     *
     * @param  string $type
     * @return string|null
     */
    public function getEntityHandlerClassNameOrDefaultClassName($type)
    {
        return $this->getEntityHandlerClassNameByType($type) ?? $this->getDefaultEntityHandlerClassName();
    }

    /**
     * Returns an instance of the current instance of the model.
     * If the instance has not been initialized yet, this will be done.
     *
     * @return EntityHandlerInterface|null
     */
    public function getInstance()
    {
        if (! isset($this->instanceOfEntity)) {
            $this->instanceOfEntity = $this->makeInstanceOfEntity();
        }

        return $this->instanceOfEntity;
    }

    /**
     * Initializes an instance of the current entity and returns it.
     *
     * @return EntityHandlerInterface|null
     */
    public function makeInstanceOfEntity()
    {
        /** @var string|mixed|null $type **/
        $type = $this->getEntityType();

        /** @var string|null $className **/
        $className = $this->getEntityHandlerClassNameOrDefaultClassName($type);

        if (! $className) {
            return null;
        }

        return $this->makeInstanceByInterfaceType($className);
    }

    /**
     * Makes an instance of the class by using the interface type.
     *
     * @param  string $className
     * @return MulticastingEntity|null
     */
    public function makeInstanceByInterfaceType(string $className)
    {
        /** @var string $interfaceType **/
        $interfaceType = $this->getInterfaceTypeOfEntities();

        switch ($interfaceType) {
            case self::INSTANCE_FROM_MODEL:
                $instance = new $className($this, $this->propertyOfEntityValues);
                break;
            case self::RELATED_ENTITY:
                $instance = new $className($this);
                break;
            case self::SIMPLE:
            default:
                $instance = new $className($this->{$this->propertyOfEntityValues});
                break;
        }

        return $instance;
    }

    /**
     * Returns an interface type that using by entities of the class.
     *
     * @return string|null
     */
    public function getInterfaceTypeOfEntities()
    {
        return $this->interfaceType ?? null;
    }
}
