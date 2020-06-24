# Multicasting of attributes

This is when some attribute can have many types. Values, which have different types of data, storage in one column.

This does your models flexible, their instances can to use an appropriate entitym and those can have different methods and algorithms for handling data. Handling data can be completely different.
This provides great possibilities for models and simplifies implementation and reading your code.

## Where can I use it?

Use it in your models of Eloquent.

**How to do this**:
- add the traits to your model;
- configure variables (set values to variables);
- implement your handlers using the base interfaces or your own interfaces.

**Example #1.1. Configuring the model**

```php
namespace App\Models;

use Dios\System\Multicasting\AttributeMulticasting;
use Dios\System\Multicasting\ReadwriteInstance; // to have the access from attributes: $model->instance
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{
    use AttributeMulticasting, ReadwriteInstance;

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
    protected $interfaceType = \Dios\System\Multicasting\Interfaces\RelatedEntity::class;

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
        'single_type' => App\Models\RelatedSheetTypes\SingleType::class,
        'roll_paper_type' => App\Models\RelatedSheetTypes\RollPaperType::class,
    ];

    /**
     * The property to read values for entities.
     *
     * @var string
     */
    protected $propertyForEntity = 'properties'; // the table has a column namesd 'properties'

    /**
     * The state of configuring instances of entities.
     *
     * @var bool
     */
    // protected $configureInstanceOfEntity = true; // by default

    /**
     * The state of filling instances of entities.
     *
     * @var bool
     */
    // protected $fillInstance = true; // by default
```

**Example #1.2. Using instances**

```php
$model = Sheet::where('type', 'single_type')->find($id);

/** @var SingleType $singleType */
$singleType = $model->instance;
$height = $singleType->getHeight();
$topMargin = $singleType->getTopMargin();
$availableHeight = $singleType->getAvailableHeight();

if ($singleType->canContain($customHeight, $customWeight)) {
    // actions
}

$singleType->setHeight($newHeight);
$singleType->save();

// The second type
$model = Sheet::where('type', 'roll_paper_type')->find($id);

/** @var RollPaperType $rollPaperType */
$rollPaperType = $model->instance;
$indent = $rollPaperType->getIndent(); // this method does not exist in SingleType
// others methods

if ($rollPaperType->canContain($customHeight, $customWeight)) {
    // other actions
}
```

## Installation

Install the package using **Composer**:

```bash
composer require "diossystem/multicasting:1.*"
```

## Setting model

### Using the trait

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

The default interface for initialization is ```\Dios\System\Multicasting\Interfaces\IndependentEntity```.

Each interface has its own features.

**The base interfaces for initialization**:
- [\Dios\System\Multicasting\Interfaces\IndependentEntity](https://github.com/diossystem/multicasting/blob/master/src/Interfaces/IndependentEntity.php) - it has no parameters.
- [\Dios\System\Multicasting\Interfaces\SimpleSingleValueEntity](https://github.com/diossystem/multicasting/blob/master/src/Interfaces/SimpleSingleValueEntity.php) - it has one parameter - one value of the model;
- [\Dios\System\Multicasting\Interfaces\SimpleArrayEntity](https://github.com/diossystem/multicasting/blob/master/src/Interfaces/SimpleArrayEntity.php) - it has one parameter - an array with values of the model;
- [\Dios\System\Multicasting\Interfaces\RelatedEntity](https://github.com/diossystem/multicasting/blob/master/src/Interfaces/RelatedEntity.php) - it has one parameter - an instance of the model;
- [\Dios\System\Multicasting\Interfaces\EntityWithModel](https://github.com/diossystem/multicasting/blob/master/src/Interfaces/EntityWithModel.php) it has one parameter - an instance of the model.

**The base interfaces for filling**:
- [\Dios\System\Multicasting\Interfaces\ArrayEntity](https://github.com/diossystem/multicasting/blob/master/src/Interfaces/ArrayEntity.php)- it is used for filling and getting an array with values.

```php
/** @var MulticastingEntity|ArrayEntity **/
$instance = $model->getInstance();

$instance->fillFromArray($values);

/** @var array $values **/
$values = $instance->toArray();
```

- [\Dios\System\Multicasting\Interfaces\SingleValueEntity](https://github.com/diossystem/multicasting/blob/master/src/Interfaces/SingleValueEntity.php) - it is used for assigning and getting a value;

```php
/** @var MulticastingEntity|SingleValueEntity **/
$instance = $model->getInstance();

$instance->setValue($value);

/** @var mixed $value **/
$value = $instance->getValue(); // returns any value
```

- [\Dios\System\Multicasting\Interfaces\KeepsEntityType](https://github.com/diossystem/multicasting/blob/master/src/Interfaces/KeepsEntityType.php) - it is used for assigning and getting an entity type.


```php
/** @var MulticastingEntity|KeepsEntityType **/
$instance = $model->getInstance();

/** @var string $type **/
$type = $instance->getEntityType();

// A type is assigned during initialization, if it is configured
$instance->setEntityType($this->getEntityType()); // it is called from the model
```

- [\Dios\System\Multicasting\Interfaces\KeepsAttributeName](https://github.com/diossystem/multicasting/blob/master/src/Interfaces/KeepsAttributeName.php) - it is used for assigning and getting an attribute name.


```php
/** @var MulticastingEntity|KeepsAttributeName **/
$instance = $model->getInstance();

/** @var string $name **/
$name = $instance->getAttributeName();

// A name is assigned during initialization, if it is configured
$instance->setAttributeName($this->{$this->propertyForEntity}); // it is called from the model
```

All these interfaces are extended from [\Dios\System\Multicasting\Interfaces\MulticastingEntity](https://github.com/diossystem/multicasting/blob/master/src/Interfaces/MulticastingEntity.php).

When you need to use your own interface or another interface it must implement ```\Dios\System\Multicasting\Interfaces\MulticastingEntity```.

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

If you uses not vendor interfaces you must extend or replace the base implementation of the ```newInstanceByClassNameOfEntity()``` function. This function implements choice an appropriate schema to initialize an instance of a class.

**Example #3. Custom interfaces**

```php
use App\Models\EntitiesOfSheets\TypeOfSheet; // your own interface
use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{
    use AttributeMulticasting {
        // Set another name to the function of the trait
        newInstanceByClassNameOfEntity as newInstanceFromTrait;
    }

    /**
     * The instance type of entities.
     *
     * @var string
     */
    protected $interfaceType = TypeOfSheet::class;

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

        if ($interfaceType === TypeOfSheet::class) {
            // The custom interface and atypical arguments are used here
            $instance = new $className($this->height, $this->height);
        } else {
            // In another case there will be call a function from the trait
            $instance = $this->newInstanceFromTrait($className);
        }
    }
}
```

To add atypical values to your instances during initialization you must extend or replace ```fillInstanceOfEntity()``` in your model.

**Example #4. A custom interface to set values**

```php
use App\Models\EntitiesOfSheets\KeepsSize; // your own interface
use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{
    use AttributeMulticasting {
        // Set another name to the function of the trait
        fillInstanceOfEntity as fillInstanceInTrait;
    }

    /**
     * Fills an instance of the entity with data from the property.
     *
     * @param  MulticastingEntity $instance
     * @return MulticastingEntity
     */
    public function fillInstanceOfEntity(MulticastingEntity $instance): MulticastingEntity
    {
        // Sets values from the model
        if ($instance instanceof KeepsSize) {
            $instance->setSize($this->height, $this->width);
            // or
            $instance->setHeight($this->height);
            $instance->setWidth($this->width);
        }

        // Uses the function of the trait
        $this->fillInstanceInTrait();
    }
}
```

### Source of a type

The next step, you must assign a source to get the current type of entities. Types are obtained from your DB.

Use ```$sourceWithEntityType``` to assign your source.

**Example #5. The double value**

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

**Example #6**

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
 * The property that contains values for entities.
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

<hr>

License: [MIT](https://github.com/diossystem/multicasting/blob/master/LICENSE)
