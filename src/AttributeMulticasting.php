<?php

namespace Dios\System\Multicasting;

use Dios\System\Multicasting\Interfaces\MulticastingEntity;
use Dios\System\Multicasting\Interfaces\EntityWithModel;
use Dios\System\Multicasting\Interfaces\RelatedEntity;
use Dios\System\Multicasting\Interfaces\SimpleEntity;
use Dios\System\Multicasting\Interfaces\SimpleArrayEntity;
use Dios\System\Multicasting\Interfaces\ArrayEntity;
use Dios\System\Multicasting\Interfaces\SingleValueEntity;
use Dios\System\Multicasting\Interfaces\KeepsEntityType;
use Dios\System\Multicasting\Interfaces\KeepsAttributeName;
use Dios\System\Multicasting\Interfaces\IndependentEntity;
use Dios\System\Multicasting\Exceptions\UndefinedCurrentInstance;
use Dios\System\Multicasting\Exceptions\UndefinedSourceOfType;
use Dios\System\Multicasting\Exceptions\UndefinedPropertyForEntities;
use Dios\System\Multicasting\Exceptions\DifferentTypesOfEntities;

/**
 * The trait handlers models that have only one attribute
 * that can body forth many possible entities.
 */
trait AttributeMulticasting
{
    /**
     * The cache of entity keys.
     *
     * @var array
     */
    protected static $entityKeyCache = [];

    /**
     * An instance of the current entity.
     *
     * @var MulticastingEntity|null
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
        $this->throwExceptionWhenUndefinedSourceOfType();

        /** @var array $sources **/
        $sources = explode('|', $this->sourceWithEntityType, 2);

        if (count($sources) === 2) {
            list($realSource, $linkToSource) = $sources;

            // Gets a key from the static cache
            if ($cache && self::hasCacheEntityKey($this->{$linkToSource})) {
                return self::getCacheOfEntityKey($this->{$linkToSource});
            }

            /** @var string|mixed|null $key **/
            $key = $this->getEntityKey($realSource);

            // Caches using $linkToSource
            if ($cache) {
                self::addCacheOfEntityKey($this->{$linkToSource}, $key);
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
     * Returns a class name of the default entity handler.
     *
     * @return string|null
     */
    public function getClassNameOfDefaultEntityHandler()
    {
        return $this->hasDefaultEntityHandler()
            ? $this->defaultEntityHandler
            : null;
        ;
    }

    /**
     * Returns a class name of an entity handler by its type.
     *
     * @param  string $type
     * @return string|null
     */
    public function getClassNameOfEntityHandlerByType($type)
    {
        return $this->hasEntityType($type)
            ? $this->entityTypeMapping[$type]
            : null
        ;
    }

    /**
     * Returns a class name of an entity handler by the type
     * or returns a class name of the default entity handler.
     *
     * @param  string $type
     * @return string|null
     */
    public function getClassNameOfEntityHandlerOrDefaultEntityHandler($type)
    {
        return $this->getClassNameOfEntityHandlerByType($type) ?? $this->getClassNameOfDefaultEntityHandler();
    }

    /**
     * Returns an instance of the current instance of the model.
     * If the instance has not been initialized yet, this will be done.
     *
     * @return MulticastingEntity|null
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
     * @return MulticastingEntity|null
     */
    public function makeInstanceOfEntity()
    {
        /** @var string|mixed|null $type **/
        $type = $this->getEntityType();

        /** @var string|null $className **/
        $className = $this->getClassNameOfEntityHandlerOrDefaultEntityHandler($type);

        if (! $className) {
            return null;
        }

        return $this->prepareNewInstanceOfEntity($className);
    }

    /**
     * Makes and configures a new instance of the entity.
     *
     * @param  string $className
     * @return MulticastingEntity|null
     */
    public function prepareNewInstanceOfEntity(string $className)
    {
        /** @var MulticastingEntity|null $instance **/
        $instance = $this->newInstanceByClassNameOfEntity($className);

        if ($instance && isset($this->configureInstanceOfEntity) && $this->configureInstanceOfEntity) {
            $instance = $this->configureInstance($instance);
        }

        return $instance;
    }

    /**
     * Makes a new instance of a class using the interface type
     * and a class name of the entity.
     *
     * @param  string $className
     * @return MulticastingEntity|null
     */
    public function newInstanceByClassNameOfEntity(string $className)
    {
        /** @var string $interfaceType **/
        $interfaceType = $this->getInterfaceTypeOfEntities();

        switch ($interfaceType) {
            case 'related_entity': // deprecated
            case RelatedEntity::class:
            case 'entity_with_model': // deprecated
            case EntityWithModel::class:
                $instance = new $className($this);
                break;
            case 'simple': // deprecated
            case SimpleEntity::class:
            case SimpleArrayEntity::class:
                $this->throwExceptionWhenUndefinedPropertyForEntities();
                $instance = new $className($this->{$this->propertyForEntity});
                break;
            case IndependentEntity::class:
            default:
                $instance = new $className;
                break;
        }

        return $instance;
    }

    /**
     * Configures the instance. Fills data from the property to the instance.
     *
     * @param  MulticastingEntity|KeepsEntityType|KeepsAttributeName $instance
     * @return MulticastingEntity
     */
    public function configureInstance(MulticastingEntity $instance): MulticastingEntity
    {
        if ($instance instanceof KeepsEntityType) {
            $instance->setEntityType($this->getEntityType());
        }

        if ($instance instanceof KeepsAttributeName) {
            $instance->setAttributeName($this->propertyForEntity);
        }

        if ($instance && isset($this->fillInstance) && $this->fillInstance) {
            $instance = $this->fillInstanceOfEntity($instance);
        }

        return $instance;
    }

    /**
     * Fills an instance of the entity with data from the property.
     *
     * @param  MulticastingEntity $instance
     * @return MulticastingEntity
     */
    public function fillInstanceOfEntity(MulticastingEntity $instance): MulticastingEntity
    {
        $this->throwExceptionWhenUndefinedPropertyForEntities();

        if ($instance instanceof SingleValueEntity) {
            $instance->setValue($this->{$this->propertyForEntity});
        }

        if ($instance instanceof ArrayEntity) {
            $instance->fillFromArray($this->{$this->propertyForEntity});
        }

        return $instance;
    }

    /**
     * Returns an interface type or a class name that using by entities.
     *
     * @return string|null
     */
    public function getInterfaceTypeOfEntities()
    {
        return $this->interfaceType ?? null;
    }

    /**
     * Updates the current instance using a given instance.
     * Both instances must be implemented using same entity.
     *
     * @param  MulticastingEntity $instance
     * @param  bool               $throwException
     *
     * @throws UndefinedCurrentInstance If the current instance is undefined.
     * @throws DifferentTypesOfEntities If the current instance and the given instance are different types.
     */
    public function updateInstance(MulticastingEntity $instance, bool $throwException = true): bool
    {
        /** @var MulticastingEntity|null $currentInstance **/
        $currentInstance = $this->getInstance();

        if (! $currentInstance) {
            if ($throwException) {
                throw new UndefinedCurrentInstance;
            }
            
            return false;
        }

        /** @var string $classNameOfCurrentInstance **/
        $classNameOfCurrentInstance = get_class($currentInstance);

        if (! ($instance instanceof $classNameOfCurrentInstance)) {
            if ($throwException) {
                throw new DifferentTypesOfEntities;
            }

            return false;
        }

        $this->instanceOfEntity = $instance;
        $this->syncInstanceWithProperty();

        return true;
    }

    /**
     * Synchronizes values of the instance with values of the property.
     * Returns a new value.
     *
     * @return mixed
     */
    public function syncInstanceWithProperty()
    {
        $this->throwExceptionWhenUndefinedPropertyForEntities();

        $currentInstance = $this->getInstance();

        if ($currentInstance) {
            if ($currentInstance instanceof ArrayEntity) {
                $this->{$this->propertyForEntity} = $currentInstance->toArray();
            } elseif ($currentInstance instanceof SingleValueEntity) {
                $this->{$this->propertyForEntity} = $currentInstance->getValue();
            }

        } else {
            $this->{$this->propertyForEntity} = null;
        }

        return $this->{$this->propertyForEntity};
    }

    /**
     * Throws the exception when the source was not assigned.
     *
     * @return void
     *
     * @throws UndefinedSourceOfType If $sourceWithEntityType is null.
     */
    public function throwExceptionWhenUndefinedSourceOfType()
    {
        if (! isset($this->sourceWithEntityType)) {
            throw new UndefinedSourceOfType;
        }
    }

    /**
     * Throws the exception when the property for entity was not assigned.
     *
     * @return void
     *
     * @throws UndefinedPropertyForEntities If $propertyForEntity is null.
     */
    public function throwExceptionWhenUndefinedPropertyForEntities()
    {
        if (! isset($this->propertyForEntity)) {
            throw new UndefinedPropertyForEntities;
        }
    }
}
