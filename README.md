# Multicasting of attributes

This is when some attribute can have many types. Values, which have different types of data, storage in one column.

This does your models flexible, their instances can to use an appropriate entitym and those can have different methods and algorithms for handling data. Handling data can be completely different.
This provides great possibilities for models and simplifies implementation and reading your code.

## Where can I use it?

Use it in your models of Eloquent.

**How to do this**:
- add the traits to your some model;
- set values to variables;
- implement your handlers using base interfaces or your own interfaces.

## Installation

Install the package using **Composer**:

```bash
composer require "diossystem/multicasting:1.*"
```

## Setting model

### Using trait

To use these features you need to add the trait to your model.

**Example #1**

```php
use Dios\System\Multicasting\AttributeMulticasting;

class Sheet extends Model
{
    use AttributeMulticasting;

    // your code
}
```

### Interface type

The next step, you must choose an interface to implement your handlers for data of the attribute the model and configure the variables.

The default interface for initialization is ```Dios\System\Multicasting\Interfaces\IndependentEntity```.

Each interface has its own features.

**The base interfaces for initialization**:
- ```Dios\System\Multicasting\Interfaces\IndependentEntity```;
- ```Dios\System\Multicasting\Interfaces\SimpleSingleValueEntity```;
- ```Dios\System\Multicasting\Interfaces\SimpleArrayEntity```;
- ```Dios\System\Multicasting\Interfaces\RelatedEntity```;
- ```Dios\System\Multicasting\Interfaces\EntityWithModel```.

**The base interfaces for filling**:
- ```Dios\System\Multicasting\Interfaces\ArrayEntity```;
- ```Dios\System\Multicasting\Interfaces\SingleValueEntity```;
- ```Dios\System\Multicasting\Interfaces\KeepsEntityType```.


All these interfaces extended from ```Dios\System\Multicasting\Interfaces\MulticastingEntity```.

The ```Dios\System\Multicasting\Interfaces\ArrayEntity``` interface contains two methods:
- ```fillFromArray(array $array)``` - it uses to fill the current instance from using an array;
- ```toArray(): array``` - it uses to get an array to save its in your DB.

When you need to use your own interface or another interface it must implement ```Dios\System\Multicasting\Interfaces\MulticastingEntity```.

Assign your chosen interface type to ```$interfaceType``` in your model.

**Example #2**

```php
use Dios\System\Multicasting\Interfaces\SimpleArrayEntity;

/**
 * The instance type of entities.
 *
 * @var string
 */
protected $interfaceType = SimpleArrayEntity::class;
}
```

Using not vendor interfaces you must extends or replace the base implementation of the ```makeInstanceByInterfaceType()``` function. This function implements choice an appropriate interface to initialize an instance of a class.

### Source of a type

The next step, you must assign a source to get the current type of entities. Types are obtained from your DB.

Use ```$sourceWithEntityType``` to assign your source.

**Example #4. The double value**

```php
/**
 * The source that contains an entity type.
 * When set second value, then may to use caching of a result of the search
 * entity key.
 *
 * Format that uses the cache: '<first_value>|<second_value>'
 * The first_value is a path to get an entity key.
 * The second_value is a key for the cache.
 * Example: 'af.type|additional_field_id'
 *
 * Format that do not use the cache: '<value>'.
 * The value is a path to get an entity key or it is a property of the current model.
 * Example: 'code_name'
 *
 * @var string
 */
protected $sourceWithEntityType = 'af.type|additional_field_id';
}
```

The package implements the two formats of sources of types:
- **single value**. It is the value to point at a source of types. The types may be in others tables and in the current table. If you will be to use its to get a type from another table, then keys of the types will not be cached.
- **double value**. It is the value to point at a source of types. The type must be in another table. The results will be cached.

Use ``single value`` to set the source of a type from the current model.
Use ``double value`` to set the source of a type that be in another table.

In the example #4 is used ``double value``: ``'af.type|additional_field_id'``.
The first value is the source of a type. ```af``` is the relation name and ```type``` is a storage of types of allowable entities.

### Handlers of entities

The next step, you must define handles of entities for allowable types.

**Example #5**

```php
/**
 * Type mapping of entity types and their handlers.
 *
 * @var array
 */
protected $entityTypeMapping = [
    'map' => \Dios\System\Page\Models\HandlersOfAdditionalFields\Map::class,
];

/**
 * A default entity handler class.
 *
 * @var string|null
 */
protected $defaultEntityHandler = \Dios\System\Page\Models\HandlersOfAdditionalFields\DefaultHandler::class;
}
```

The ```$entityTypeMapping``` and ```$defaultEntityHandler``` variables must contain handlers for entities. You can use one handler for different types.

The default handler is optional.

These handlers must implement the MulticastingEntity interface.

### Property containing a value

You must define the ```$propertyForEntity``` variable. It contains a property of the model to get values for instances of entities (handlers). It is used during initialization an instances.


```php
/**
 * The property that contains values to an entity.
 *
 * @var string
 */
protected $propertyForEntity = 'values';
```

Often your property will be belong to the 'array' type.

```php
/**
 * The attributes that should be cast to native types.
 *
 * @var array
 */
protected $casts = [
    'values' => 'array',
];
```

You may use any type and values of the property will be passed to a new instance of the entity.
