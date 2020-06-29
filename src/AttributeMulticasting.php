<?php

namespace Dios\System\Multicasting;

use Dios\System\Multicasting\Interfaces\MulticastingEntity;
use Dios\System\Multicasting\Interfaces\EntityWithModel;
use Dios\System\Multicasting\Interfaces\RelatedEntity;
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
use Dios\System\Multicasting\Exceptions\UndefinedEntityTypeMapping;

/**
 * The trait handlers models that have only one attribute
 * that can body forth many possible entities.
 */
trait AttributeMulticasting
{
    /**
     * The cache of entity keys.
     *
     * Example:
     * 1 => 'map',
     * 2 => 'images',
     * or
     * 'map' => 'map',
     * 'images' => 'images',
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
     * Returns a property for an entity.
     *
     * @return string
     */
    public function getPropertyForEntity()
    {
        $this->throwExceptionWhenUndefinedPropertyForEntities();

        return $this->propertyForEntity;
    }

    /**
     * Returns an array with supported types of entities.
     *
     * @return array|string[]
     */
    public function getTypesOfEntities(): array
    {
        $this->throwExceptionWhenUndefinedEntityTypeMapping();

        return array_keys($this->entityTypeMapping);
    }

    /**
     * Returns an entity type by the given key (an index).
     *
     * @param  mixed|string|int $key
     * @return string|null
     */
    public function getEntityTypeByKey($key, bool $cache = true)
    {
        $this->throwExceptionWhenUndefinedSourceOfType();

        if ($cache && self::hasCacheEntityKey($key)) {
            return self::getCacheOfEntityKey($key);
        }

        $source = new Source($this->sourceWithEntityType, $this);

        /** @var string|mixed|null $type **/
        $type = $source->getEntityTypeByKey($key);

        if ($cache && $type) {
            self::addCacheOfEntityKey($this->{$source->getSourceOfKey()}, $type);
        }

        return $type;
    }

    /**
     * Returns an entity type.
     *
     * @param  bool   $cache
     * @return string|mixed|null
     */
    public function getEntityType(bool $cache = true)
    {
        $this->throwExceptionWhenUndefinedSourceOfType();

        $source = new Source($this->sourceWithEntityType, $this);

        /** @var string $sourceOfKey */
        $sourceOfKey = $source->getSourceOfKey();

        return $this->getEntityTypeByKey($this->{$sourceOfKey}, $cache);
    }

    /**
     * Returns an entity key by the given type.
     *
     * @param  mixed|string|int|null $type
     * @param  bool $cache
     * @return mixed|string|int|null
     */
    public function getEntityKeyByType($type, bool $cache = true)
    {
        $this->throwExceptionWhenUndefinedSourceOfType();

        if ($cache && self::hasCacheOfEntityTypeByKey($type)) {
            return self::getCacheOfEntityTypeByKey($type);
        }

        $source = new Source($this->sourceWithEntityType, $this);

        /** @var string|int|null $key */
        $key = $source->getEntityKeyByType($type);

        if ($cache && $type) {
            self::addCacheOfEntityKey($key, $type);
        }

        return $key;
    }

    /**
     * Returns entity types.
     *
     * @param  bool $onlySupportedTypes
     * @return array|string[]|int[]
     */
    public function getEntityTypes(bool $onlySupportedTypes = true): array
    {
        if ($onlySupportedTypes) {
            return $this->getTypesOfEntities();
        }

        return array_values($this->getEntityTypesWithKeys($onlySupportedTypes));
    }

    /**
     * Returns entity types with keys.
     * Keys (indexes) are keys of the array.
     *
     * @param  bool $onlySupportedTypes
     * @return array
     */
    public function getEntityTypesWithKeys(bool $onlySupportedTypes = true): array
    {
        $source = new Source($this->sourceWithEntityType, $this);

        /** @var array|string[] $types */
        $types = $source->getEntityTypes();

        if ($onlySupportedTypes) {
            /** @var array|string[] $supportedTypes */
            $supportedTypes = $this->getTypesOfEntities();

            $types = array_filter($types, function ($type) use ($supportedTypes) {
                return in_array($type, $supportedTypes);
            });
        }

        return $types;
    }

    /**
     * Returns an array with entity keys.
     *
     * @return array|string[]|int[]
     */
    public function getEntityKeys(bool $onlySupportedTypes = true): array
    {
        return array_values($this->getEntityKeysWithTypes($onlySupportedTypes));
    }

    /**
     * Returns entity keys with types.
     * Types are keys of the array.
     *
     * @param  bool $onlySupportedTypes
     * @return array|string[]|int[]
     */
    public function getEntityKeysWithTypes(bool $onlySupportedTypes = true): array
    {
        $types = $this->getEntityTypesWithKeys($onlySupportedTypes);

        return array_combine(array_values($types), array_keys($types));
    }

    /**
     * Adds a new value of cache of entity keys.
     *
     * @param string|mixed $key         A key or an index of the type.
     * @param string|mixed|null $type
     */
    protected static function addCacheOfEntityKey($key, $type)
    {
        self::$entityKeyCache[$key] = $type;
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
     * Returns an entity key by the given type.
     *
     * @param  string|mixed $type
     * @return string|mixed|null
     */
    public static function getCacheOfEntityTypeByKey($type)
    {
        /** @var string|int|bool $key */
        $key = array_search($type, self::$entityKeyCache, true);

        return $key === false ? null : $key;
    }

    /**
     * Checks whether a cache of an entity type exists.
     *
     * @param  string|mixed $type
     * @return bool
     */
    public static function hasCacheOfEntityTypeByKey($type): bool
    {
        /** @var string|int|bool $key */
        $key = array_search($type, self::$entityKeyCache, true);

        return $key === false ? null : $key;
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
     * @param  string|int|null $type
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
     * @param  string|int|null $type
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
     * Sets a new instance to the model.
     *
     * @param  MulticastingEntity $instance
     * @return void
     */
    public function setInstance(MulticastingEntity $instance)
    {
        $this->instanceOfEntity = $instance;
        $this->syncInstanceWithProperty();
        // Updates instance to set a correct instance
        $this->instanceOfEntity = $this->makeInstanceOfEntity();
    }

    /**
     * Initializes a new instance by the entity type and returns it.
     * If $prepare is true, then prepares the new instance.
     *
     * @param  string $type
     * @param  bool $prepare
     * @return MulticastingEntity|null
     */
    public function initializeInstanceByEntityType(string $type, bool $prepare = false)
    {
        $this->instanceOfEntity = $prepare
            ? $this->makeInstanceByEntityType($type)
            : $this->newInstanceByEntityType($type)
        ;

        return $this->instanceOfEntity;
    }

    /**
     * Initializes an instance of the current entity.
     * Prepares and returns it.
     *
     * @return MulticastingEntity|null
     */
    public function makeInstanceOfEntity()
    {
        /** @var string|mixed|null $type **/
        $type = $this->getEntityType();

        return $this->makeInstanceByEntityType($type);
    }

    /**
     * Initializes a new instance by the entity type.
     * Prepares and returns it.
     *
     * @param  string $type
     * @return MulticastingEntity|null
     */
    public function makeInstanceByEntityType(string $type)
    {
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

        if ($instance && $this->isThereNeedToConfigure()) {
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
            case RelatedEntity::class:
            case EntityWithModel::class:
                $instance = new $className($this);
                break;
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
     * Makes a new instance by the entity type.
     * Does not prepare the new instance.
     *
     * @param  string $type
     * @return MulticastingEntity|null
     */
    public function newInstanceByEntityType(string $type)
    {
        /** @var string|null $className **/
        $className = $this->getClassNameOfEntityHandlerOrDefaultEntityHandler($type);

        return $this->newInstanceByClassNameOfEntity($className);
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

        if ($instance && $this->isThereNeedToFill()) {
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

        $this->setInstance($instance);

        return true;
    }

    public function setInstanceWithNewType(string $type, MulticastingEntity $instance)
    {
        $this->changeEntityType($type);
        // TODO меняет тип сущности на основе текущих соответствий
        // Задает новую текущую сущность
        // Заполняет значения из текущей сущности
    }

    /**
     * Changes the current type with the new type
     * and resets the current instance and its data.
     */
    public function changeEntityType(string $type)
    {
        $this->type = $type; // fix

        if (true) {
            // TODO На основе заданного типа найти ключ/индекс
            // и установить его текущей сущности
        } else {
            $this->instanceOfEntity = null;
        }

        $this->resetDataOfProperty();

        return $state ?? false;
    }

    public function replaceTypeAndInstance(string $type, MulticastingEntity $instance)
    {
        $this->changeEntityType($type);
        $this->setInstance($instance);
    }

    public function replaceTypeAndFillInstance(string $type, $data)
    {
        if (! $this->changeEntityType($type)) {
            return false; // or exception unsupported type
        }

        $instance = $this->newInstanceByEntityType($type);
        $this->setInstance($instance);

        return $instance;
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
     * Resets data of the property.
     *
     * @return void
     */
    public function resetDataOfProperty()
    {
        $this->throwExceptionWhenUndefinedPropertyForEntities();
        $this->{$this->propertyForEntity} = null;
    }

    /**
     * Throws the exception when the map was not assigned.
     *
     * @throws UndefinedEntityTypeMapping
     */
    public function throwExceptionWhenUndefinedEntityTypeMapping()
    {
        if (! isset($this->entityTypeMapping)) {
            throw new UndefinedEntityTypeMapping();
        }
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

    /**
     * Checks whether the current instance needs to be configured.
     *
     * @return bool
     */
    public function isThereNeedToConfigure(): bool
    {
         return ! isset($this->configureInstanceOfEntity)
            || isset($this->configureInstanceOfEntity) && $this->configureInstanceOfEntity
        ;
    }

    /**
     * Checks whether the current instance need to be filled.
     *
     * @return bool
     */
    public function isThereNeedToFill(): bool
    {
        return ! isset($this->fillInstance)
            || isset($this->fillInstance) && $this->fillInstance
        ;
    }
}
