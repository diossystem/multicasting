<?php

use Illuminate\Database\Seeder;
use Tests\Models\Sheet;

class SheetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Sheet::class)->create([
            'type' => Sheet::SINGLE_TYPE,
            'name' => 'Sheet with a single type',
            'height' => 200,
            'width' => 150,
            'properties' => [
                'margin_top' => 10,
                'margin_bottom' => 15,
                'margin_left' => 30,
                'margin_right' => 20,
            ],
        ]);

        factory(Sheet::class)->create([
            'type' => Sheet::ROLL_PAPER_TYPE,
            'name' => 'Roll of paper',
            'height' => 200000,
            'width' => 200,
            'properties' => [
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
                'indent' => 10,
            ],
        ]);

        factory(Sheet::class)->create([
            'type' => 'unknown',
            'name' => 'Nonstandart',
            'height' => 135,
            'width' => 135,
            'properties' => [
                'indent' => 5,
                'bore' => 10,
            ],
        ]);
    }
}
