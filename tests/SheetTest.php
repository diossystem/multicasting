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

        /** @var EntityWithModel $instance **/
        $instance = $sheet->instance;

        $this->assertInstanceOf(SingleType::class, $instance);
    }

    public function testInstanceOfRollPaperType()
    {
        /** @var Sheet $sheet **/
        $sheet = $this->getSheetByType(Sheet::ROLL_PAPER_TYPE);

        /** @var EntityWithModel $instance **/
        $instance = $sheet->instance;

        $this->assertInstanceOf(RollPaperType::class, $instance);
    }

    public function testInstanceOfNonstandardType()
    {
        /** @var Sheet $sheet **/
        $sheet = $this->getSheetByType('unknown');

        /** @var EntityWithModel|null $instance **/
        $instance = $sheet->instance;

        $this->assertNull($instance);
    }

    public function getSheetByType(string $type)
    {
        return Sheet::type($type)->first();
    }
}
