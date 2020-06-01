<?php

namespace Tests;

use SheetsTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Models\IndependentSheet;
use Dios\System\Multicasting\Interfaces\ArrayEntity;
use Dios\System\Multicasting\Interfaces\MulticastingEntity;
use Dios\System\Multicasting\Interfaces\IndependentEntity;
use Tests\Models\IndependentSheetTypes\SingleType;
use Tests\TestCase;

class IndependentSheetTest extends TestCase
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
        $attributes = array_keys(IndependentSheet::first()->getOriginal());

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
        /** @var IndependentSheet $sheet **/
        $sheet = $this->getSheetByType(IndependentSheet::SINGLE_TYPE);

        /** @var IndependentEntity $instance **/
        $instance = $sheet->instance;

        $this->assertInstanceOf(IndependentEntity::class, $instance);
        $this->assertInstanceOf(MulticastingEntity::class, $instance);
        $this->assertInstanceOf(ArrayEntity::class, $instance);
    }
    
    public function testFillFromArray()
    {
        /** @var IndependentSheet $sheet **/
        $sheet = $this->getSheetByType(IndependentSheet::SINGLE_TYPE);

        /** @var IndependentEntity|SingleType $instance **/
        $instance = $sheet->instance;

        $margins = [
            'margin_top' => 3,
            'margin_bottom' => 6,
            'margin_left' => 12,
            'margin_right' => 18,
        ];

        $instance->fillFromArray($margins);

        $this->assertEquals($margins, $instance->toArray());
    }

    public function getSheetByType(string $type)
    {
        return IndependentSheet::type($type)->first();
    }
}
