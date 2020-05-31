<?php

namespace Tests;

use SheetsTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Models\Sheet;
use Tests\Models\SheetTypes\SingleType;
use Tests\Models\SheetTypes\RollPaperType;
use Dios\System\Multicasting\Interfaces\EntityWithModel;
use Tests\TestCase;

class SheetTest extends TestCase
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
        $attributes = array_keys(Sheet::first()->getOriginal());

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
        /** @var Sheet $sheet **/
        $sheet = $this->getSheetByType(Sheet::SINGLE_TYPE);

        /** @var EntityWithModel $instance **/
        $instance = $sheet->instance;

        $this->assertInstanceOf(EntityWithModel::class, $instance);
    }

    public function testInstanceOfSingleType()
    {
        /** @var Sheet $sheet **/
        $sheet = $this->getSheetByType(Sheet::SINGLE_TYPE);

        /** @var EntityWithModel|SingleType $instance **/
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
        /** @var Sheet $sheet **/
        $sheet = $this->getSheetByType(Sheet::ROLL_PAPER_TYPE);

        /** @var EntityWithModel|RollPaperType $instance **/
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
        /** @var Sheet $sheet **/
        $sheet = $this->getSheetByType('unknown');

        /** @var EntityWithModel|null $instance **/
        $instance = $sheet->instance;

        $this->assertNull($instance);

        $this->assertEquals([
            'indent' => 5,
            'bore' => 10,
        ], $sheet->properties);
    }

    public function getSheetByType(string $type)
    {
        return Sheet::type($type)->first();
    }
}
