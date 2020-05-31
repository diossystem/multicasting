<?php

namespace Tests;

use SheetsTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Models\RelatedSheet;
use Dios\System\Multicasting\Interfaces\ArrayEntity;
use Dios\System\Multicasting\Interfaces\KeepsEntityType;
use Dios\System\Multicasting\Interfaces\MulticastingEntity;
use Dios\System\Multicasting\Interfaces\RelatedEntity;
use Tests\Models\RelatedSheetTypes\SingleType;
use Tests\Models\RelatedSheetTypes\RollPaperType;
use Tests\TestCase;

class RelatedSheetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->loadBaseMigrations();
        $this->seed(SheetsTableSeeder::class);
    }

    public function testStructure()
    {
        /** @var array|string[] $attributes **/
        $attributes = array_keys(RelatedSheet::first()->getOriginal());

        $this->assertEquals([
            'id',
            'type',
            'name',
            'height',
            'width',
            'properties',
        ], $attributes);
    }

    public function testInterface()
    {
        /** @var RelatedSheet $sheet **/
        $sheet = $this->getSheetByType(RelatedSheet::SINGLE_TYPE);

        /** @var RelatedEntity $instance **/
        $instance = $sheet->instance;

        $this->assertInstanceOf(RelatedEntity::class, $instance);
        $this->assertInstanceOf(KeepsEntityType::class, $instance);
        $this->assertInstanceOf(MulticastingEntity::class, $instance);
        $this->assertInstanceOf(ArrayEntity::class, $instance);
    }

    public function testSave()
    {
        /** @var RelatedSheet $sheet **/
        $sheet = $this->getSheetByType(RelatedSheet::SINGLE_TYPE);

        /** @var RelatedEntity|SingleType $instance **/
        $instance = $sheet->instance;

        $this->assertEquals(200, $instance->getHeight());
        $this->assertEquals(150, $instance->getWidth());
        $this->assertEquals(10, $instance->getTopMargin());
        $this->assertEquals(15, $instance->getBottomMargin());

        $instance->setHeight(400);
        $instance->setWidth(200);
        $instance->setTopMargin(5);
        $instance->setBottomMargin(5);
        $instance->save();

        /** @var RelatedSheet $sheet **/
        $sheet = $this->getSheetByType(RelatedSheet::SINGLE_TYPE);
        
        $this->assertEquals([
            'id' => 1,
            'type' => 'single',
            'name' => 'Sheet with a single type',
            'height' => 400,
            'width' => 200,
            'properties' => [
                'margin_top' => 5,
                'margin_bottom' => 5,
                'margin_left' => 30,
                'margin_right' => 20,
            ],
        ], $sheet->toArray());

        /** @var RelatedEntity|SingleType $instance **/
        $instance = $sheet->instance;
        
        $this->assertEquals(400, $instance->getHeight());
        $this->assertEquals(200, $instance->getWidth());
        $this->assertEquals(5, $instance->getTopMargin());
        $this->assertEquals(5, $instance->getBottomMargin());

        $this->assertEquals([
            'height' => 400,
            'width' => 200,
            'available_width' => 150,
            'available_height' => 390,
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_left' => 30,
            'margin_right' => 20,
        ], $instance->toArray());
    }

    public function testGetReference()
    {
        /** @var RelatedSheet $sheet **/
        $sheet = $this->getSheetByType(RelatedSheet::SINGLE_TYPE);

        /** @var RelatedEntity|SingleType $instance **/
        $instance = $sheet->instance;

        $this->assertEquals($instance->getModel(), $instance->getReference());

        $copiedModel = $instance->getModel();
        $copiedModel->height = 500;

        $this->assertNotEquals($copiedModel->toArray(), $instance->getReference()->toArray());
    }

    public function testFillFromArray()
    {
        /** @var RelatedSheet $sheet **/
        $sheet = $this->getSheetByType(RelatedSheet::SINGLE_TYPE);

        /** @var RelatedEntity|SingleType $instance **/
        $instance = $sheet->instance;

        $margins = [
            'margin_top' => 3,
            'margin_bottom' => 6,
            'margin_left' => 12,
            'margin_right' => 18,
        ];

        $instance->fillFromArray($margins + [
            'height' => 999,
            'width' => 111,
        ]);

        $this->assertEquals($margins, $instance->getArrayWithMargins());
        $this->assertEquals(999, $instance->getHeight());
        $this->assertEquals(111, $instance->getWidth());
    }

    public function testGetEntityType()
    {
        /** @var RelatedSheet $sheet **/
        $sheet = $this->getSheetByType(RelatedSheet::SINGLE_TYPE);

        /** @var RelatedEntity|SingleType $instance **/
        $instance = $sheet->instance;

        // $this->assertEquals(RelatedSheet::SINGLE_TYPE, $instance->getEntityType());
    }

    public function testInstanceOfSingleType()
    {
        /** @var RelatedSheet $sheet **/
        $sheet = $this->getSheetByType(RelatedSheet::SINGLE_TYPE);

        /** @var RelatedEntity|SingleType $instance **/
        $instance = $sheet->instance;

        $this->assertInstanceOf(SingleType::class, $instance);

        $this->assertEquals(200, $instance->getHeight());
        $this->assertEquals(150, $instance->getWidth());
        $this->assertEquals(10, $instance->getTopMargin());
        $this->assertEquals(15, $instance->getBottomMargin());
        $this->assertEquals(30, $instance->getLeftMargin());
        $this->assertEquals(20, $instance->getRightMargin());
        $this->assertEquals(175, $instance->getAvailableHeight());
        $this->assertEquals(100, $instance->getAvailableWidth());

        $this->assertFalse($instance->canContain(-100, -150));
        $this->assertFalse($instance->canContain(0, 0));
        $this->assertTrue($instance->canContain(150, 100));
        $this->assertTrue($instance->canContain(175, 100));
        $this->assertFalse($instance->canContain(176, 100));
        $this->assertFalse($instance->canContain(175, 101));
        $this->assertFalse($instance->canContain(176, 101));
    }

    public function testInstanceOfRollPaperType()
    {
        /** @var RelatedSheet $sheet **/
        $sheet = $this->getSheetByType(RelatedSheet::ROLL_PAPER_TYPE);

        /** @var RelatedSheet|RollPaperType $instance **/
        $instance = $sheet->instance;

        $this->assertInstanceOf(RollPaperType::class, $instance);

        $this->assertEquals(15000, $instance->getWidth());
        $this->assertEquals(200000, $instance->getHeight());
        $this->assertEquals(10, $instance->getTopMargin());
        $this->assertEquals(10, $instance->getBottomMargin());
        $this->assertEquals(10, $instance->getLeftMargin());
        $this->assertEquals(10, $instance->getRightMargin());
        $this->assertEquals(10, $instance->getIndent());

        $this->assertFalse($instance->canContain(-100, -150));
        $this->assertFalse($instance->canContain(0, 0));
        $this->assertTrue($instance->canContain(10000, 14980));
        $this->assertTrue($instance->canContain(199980, 14980));
        $this->assertFalse($instance->canContain(199981, 14981));
        $this->assertFalse($instance->canContain(200001, 14980));
        $this->assertFalse($instance->canContain(199980, 14981));
    }

    public function testInstanceOfNonstandardType()
    {
        /** @var RelatedSheet $sheet **/
        $sheet = $this->getSheetByType('unknown');

        /** @var RelatedSheet|null $instance **/
        $instance = $sheet->instance;

        $this->assertNull($instance);

        $this->assertEquals([
            'indent' => 5,
            'bore' => 10,
        ], $sheet->properties);
    }

    public function getSheetByType(string $type)
    {
        return RelatedSheet::type($type)->first();
    }
}
