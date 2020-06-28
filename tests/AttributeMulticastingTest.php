<?php

namespace Tests;

use AdditionalFieldsTableSeeder;
use SheetsTableSeeder;
use Dios\System\Multicasting\AttributeMulticasting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Models\AdditionalFieldsOfPages;
use Tests\Models\Sheet;
use Tests\TestCase;

class AttributeMulticastingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->loadBaseMigrations();
        $this->seed(AdditionalFieldsTableSeeder::class);
        $this->seed(SheetsTableSeeder::class);
    }

    /**
     * @param  string $className
     * @param  array  $types
     * @return void
     *
     * @dataProvider getTypesProvider
     */
    public function testGetTypes(string $className, array $types)
    {
        /** @var AdditionalFieldsOfPages $af */
        $af = new $className;

        $this->assertCount(count($types), $af->getTypesOfEntities());
        $this->assertEquals($types, $af->getTypesOfEntities());
    }

    public function getTypesProvider(): array
    {
        return [
            [
                AdditionalFieldsOfPages::class,
                [
                    'map',
                    'images',
                ],
            ],
            [
                Sheet::class,
                [
                    Sheet::SINGLE_TYPE,
                    Sheet::ROLL_PAPER_TYPE,
                ]
            ],
        ];
    }

    /**
     * @param  string $class
     * @param  string|int $key
     * @param  string|null $type
     * @return void
     *
     * @dataProvider getEntityTypeByKeyProvider
     */
    public function testGetEntityTypeByKey(string $class, $key, $type)
    {
        /** @var Model|AttributeMulticasting $instance */
        $instance = new $class;

        $this->assertEquals($type, $instance->getEntityTypeByKey($key, false));

    }

    public function getEntityTypeByKeyProvider(): array
    {
        return [
            [
                AdditionalFieldsOfPages::class,
                2,
                'images',
            ],
            [
                AdditionalFieldsOfPages::class,
                3,
                'custom',
            ],
            [
                AdditionalFieldsOfPages::class,
                1,
                'map',
            ],
            [
                AdditionalFieldsOfPages::class,
                15,
                null,
            ],
            [
                Sheet::class,
                Sheet::ROLL_PAPER_TYPE,
                Sheet::ROLL_PAPER_TYPE,
            ],
            [
                Sheet::class,
                'unknown',
                'unknown',
            ],
            [
                Sheet::class,
                'undefined',
                null,
            ]
        ];
    }

    /**
     * @param  string $class
     * @param  string|int|null $type
     * @param  string|int|null $key
     * @return void
     *
     * @dataProvider getEntityKeyByTypeProvider
     */
    public function testGetEntityKeyByType(string $class, $type, $key)
    {
        /** @var Model|AttributeMulticasting $instance */
        $instance = new $class;

        $this->assertEquals($key, $instance->getEntityKeyByType($type, false));
    }

    public function getEntityKeyByTypeProvider(): array
    {
        return [
            [
                AdditionalFieldsOfPages::class,
                'images',
                2,
            ],
            [
                AdditionalFieldsOfPages::class,
                'custom',
                3,
            ],
            [
                AdditionalFieldsOfPages::class,
                'map',
                1,
            ],
            [
                AdditionalFieldsOfPages::class,
                'undefined',
                null,
            ],
            [
                Sheet::class,
                Sheet::ROLL_PAPER_TYPE,
                Sheet::ROLL_PAPER_TYPE,
            ],
            [
                Sheet::class,
                'unknown',
                'unknown',
            ],

            [
                Sheet::class,
                'undefined',
                null,
            ],
        ];
    }

    /**
     * @param  string $className
     * @param  array $keys
     * @param  bool $supportedKeys
     * @return void
     *
     * @dataProvider getEnityKeysWithTypesProvider
     */
    public function testGetEntityKeysWithTypes(string $className, array $keys, bool $supportedKeys)
    {
        $instance = new $className;
        $this->assertEquals($keys, $instance->getEntityKeysWithTypes($supportedKeys));
    }

    public function getEnityKeysWithTypesProvider(): array
    {
        return [
            [
                AdditionalFieldsOfPages::class,
                [
                    'custom' => 3,
                    'images' => 2,
                    'local_pages' => 6,
                    'map' => 1,
                ],
                false
            ],
            [
                AdditionalFieldsOfPages::class,
                [
                    'images' => 2,
                    'map' => 1,
                ],
                true
            ],
            [
                Sheet::class,
                [
                    Sheet::ROLL_PAPER_TYPE => Sheet::ROLL_PAPER_TYPE,
                    Sheet::SINGLE_TYPE => Sheet::SINGLE_TYPE,
                    'unknown' => 'unknown',
                ],
                false,
            ],
            [
                Sheet::class,
                [
                    Sheet::ROLL_PAPER_TYPE => Sheet::ROLL_PAPER_TYPE,
                    Sheet::SINGLE_TYPE => Sheet::SINGLE_TYPE,
                ],
                true,
            ],
        ];
    }

    /**
     * @param  string $className
     * @param  array $types
     * @param  bool $supportedKeys
     * @return void
     *
     * @dataProvider getTypesWithKeysProvider
     */
    public function testGetTypesWithKeys(string $className, array $types, bool $supportedKeys)
    {
        $instance = new $className;
        $this->assertEquals($types, $instance->getEntityTypesWithKeys($supportedKeys));
    }

    public function getTypesWithKeysProvider(): array
    {
        return [
            [
                AdditionalFieldsOfPages::class,
                [
                    1 => 'map',
                    2 => 'images',
                    3 => 'custom',
                    6 => 'local_pages'
                ],
                false
            ],
            [
                AdditionalFieldsOfPages::class,
                [
                    1 => 'map',
                    2 => 'images',
                ],
                true
            ],
            [
                Sheet::class,
                [
                    Sheet::ROLL_PAPER_TYPE => Sheet::ROLL_PAPER_TYPE,
                    Sheet::SINGLE_TYPE => Sheet::SINGLE_TYPE,
                    'unknown' => 'unknown',
                ],
                false,
            ],
            [
                Sheet::class,
                [
                    Sheet::ROLL_PAPER_TYPE => Sheet::ROLL_PAPER_TYPE,
                    Sheet::SINGLE_TYPE => Sheet::SINGLE_TYPE,
                ],
                true,
            ],
        ];
    }

    /**
     * @param  string $className
     * @param  string $type
     * @param  bool $expected
     * @return void
     *
     * @dataProvider hasEntityTypeProvider
     */
    public function testHasEntityType(string $className, string $type, bool $expected)
    {
        /** @var AttributeMulticasting $instance */
        $instance = new $className;
        $this->assertEquals($expected, $instance->hasEntityType($type));
    }

    public function hasEntityTypeProvider(): array
    {
        return [
            [
                AdditionalFieldsOfPages::class,
                'images',
                true
            ],
            [
                AdditionalFieldsOfPages::class,
                'Images',
                false
            ],
            [
                AdditionalFieldsOfPages::class,
                'custom',
                false
            ],
            [
                Sheet::class,
                Sheet::SINGLE_TYPE,
                true
            ],
            [
                Sheet::class,
                Sheet::ROLL_PAPER_TYPE,
                true
            ],
            [
                Sheet::class,
                'unknown',
                false
            ],
        ];
    }

    /**
     * @param  string $className
     * @param  bool $expected
     * @return void
     *
     * @dataProvider hasDefaultEntityHandlerProvider
     */
    public function testHasDefaultEntityHandler(string $className, bool $expected)
    {
        /** @var AttributeMulticasting $instance */
        $instance = new $className;
        $this->assertEquals($expected, $instance->hasDefaultEntityHandler());
    }

    public function hasDefaultEntityHandlerProvider(): array
    {
        return [
            [
                AdditionalFieldsOfPages::class,
                true
            ],
            [
                Sheet::class,
                false
            ],
        ];
    }

    /**
     * @param  string $className
     * @param  string|null $expected
     * @return void
     *
     * @dataProvider getClassNameOfDefaultEntityHandlerProvider
     */
    public function testGetClassNameOfDefaultEntityHandler(string $className, string $expected = null)
    {
        /** @var AttributeMulticasting $instance */
        $instance = new $className;
        $this->assertEquals($expected, $instance->getClassNameOfDefaultEntityHandler());
    }

    public function getClassNameOfDefaultEntityHandlerProvider(): array
    {
        return [
            [
                AdditionalFieldsOfPages::class,
                \Tests\Models\AdditionalFieldHandlers\DefaultHandler::class
            ],
            [
                Sheet::class,
                null
            ],
        ];
    }

    /**
     * @param  string $className
     * @param  string|int|null $type
     * @param  string $expected
     * @return void
     *
     * @dataProvider getClassNameOfEntityHandlerByTypeProvider
     */
    public function testGetClassNameOfEntityHandlerByType(string $className, $type = null, string $expected = null)
    {
        /** @var AttributeMulticasting $instance */
        $instance = new $className;
        $this->assertEquals($expected, $instance->getClassNameOfEntityHandlerByType($type));
    }

    public function getClassNameOfEntityHandlerByTypeProvider(): array
    {
        return [
            [
                AdditionalFieldsOfPages::class,
                'map',
                \Tests\Models\AdditionalFieldHandlers\Map::class,
            ],
            [
                AdditionalFieldsOfPages::class,
                'images',
                \Tests\Models\AdditionalFieldHandlers\Images::class,
            ],
            [
                AdditionalFieldsOfPages::class,
                null,
                null,
            ],
            [
                AdditionalFieldsOfPages::class,
                'custom',
                null,
            ],
            [
                AdditionalFieldsOfPages::class,
                'undefined',
                null,
            ],
            [
                Sheet::class,
                Sheet::ROLL_PAPER_TYPE,
                \Tests\Models\SheetTypes\RollPaperType::class,
            ],
        ];
    }

    /**
     * @param  string $className
     * @param  string|int|null $type
     * @param  string $expected
     * @return void
     *
     * @dataProvider getClassNameOfEntityHandlerOrDefaultEntityHandlerProvider
     */
    public function testGetClassNameOfEntityHandlerOrDefaultEntityHandler(
        string $className,
        $type = null,
        string $expected = null
    ) {
        /** @var AttributeMulticasting $instance */
        $instance = new $className;
        $this->assertEquals($expected, $instance->getClassNameOfEntityHandlerOrDefaultEntityHandler($type));
    }

    public function getClassNameOfEntityHandlerOrDefaultEntityHandlerProvider(): array
    {
        return [
            [
                AdditionalFieldsOfPages::class,
                'map',
                \Tests\Models\AdditionalFieldHandlers\Map::class,
            ],
            [
                AdditionalFieldsOfPages::class,
                'images',
                \Tests\Models\AdditionalFieldHandlers\Images::class,
            ],
            [
                AdditionalFieldsOfPages::class,
                null,
                \Tests\Models\AdditionalFieldHandlers\DefaultHandler::class,
            ],
            [
                AdditionalFieldsOfPages::class,
                'custom',
                \Tests\Models\AdditionalFieldHandlers\DefaultHandler::class,
            ],
            [
                AdditionalFieldsOfPages::class,
                'undefined',
                \Tests\Models\AdditionalFieldHandlers\DefaultHandler::class,
            ],
            [
                Sheet::class,
                Sheet::ROLL_PAPER_TYPE,
                \Tests\Models\SheetTypes\RollPaperType::class,
            ],
        ];
    }

    /**
     * @param  string $key
     * @param  string|null $entityClassName
     * @return void
     *
     * @dataProvider getInstanceOfSheetProvider
     */
    public function testGetInstanceOfSheet(string $key, string $entityClassName = null)
    {
        /** @var Sheet|AttributeMulticasting $model */
        $model = Sheet::where('type', $key)->first();

        if ($entityClassName) {
            $handler = new $entityClassName($model);
            $this->assertEquals($handler, $model->getInstance());
        } else {
            $this->assertNull($model->getInstance());
        }
    }

    public function getInstanceOfSheetProvider(): array
    {
        return [
            [
                Sheet::ROLL_PAPER_TYPE,
                \Tests\Models\SheetTypes\RollPaperType::class,
            ],
            [
                Sheet::SINGLE_TYPE,
                \Tests\Models\SheetTypes\SingleType::class,
            ],
            [
                'unknown',
                null,
            ],
        ];
    }

    /**
     * @param  int $key
     * @param  string|null $entityClassName
     * @return void
     *
     * @dataProvider getInstanceOfAdditionalFieldsProvider
     */
    public function testGetInstanceOfAdditionalFields(
        int $key,
        string $entityClassName = null
    ) {
        /** @var AdditionalFieldsOfPages|AttributeMulticasting $model */
        $model = AdditionalFieldsOfPages::where('additional_field_id', $key)->first();

        if ($entityClassName) {
            $handler = new $entityClassName($model->values);
            $this->assertEquals($handler, $model->getInstance());
        } else {
            $this->assertNull($model->getInstance());
        }
    }

    public function getInstanceOfAdditionalFieldsProvider(): array
    {
        return [
            'map' => [
                1,
                \Tests\Models\AdditionalFieldHandlers\Map::class,
            ],
            'images' => [
                2,
                \Tests\Models\AdditionalFieldHandlers\Images::class,
            ],
            'custom' => [
                3,
                \Tests\Models\AdditionalFieldHandlers\DefaultHandler::class,
            ],
        ];
    }

    // TODO
    // 4. get
    // 5. Test cache
    // 5. default entity handler
    // 6. Test each function.
    // 7. Test exceptions
    // 8. Test working of models and instances
    // Use empty model, full model, model with default properties
}
