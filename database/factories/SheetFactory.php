<?php

use Faker\Generator as Faker;
use Tests\Models\Sheet;
use Illuminate\Support\Str;

$factory->define(Sheet::class, function (Faker $faker) {
    $type = rand(0, 1);

    $properties = [
        'margin_top' => 10,
        'margin_bottom' => 10,
        'margin_left' => 10,
        'margin_right' => 10,
    ];

    if ($type) {
        $height = $faker->numberBetween(150, 500);
    } else {
        $height = $faker->numberBetween(1000, 50000);
        $properties += [
            'indent' => 10,
        ];
    }

    return [
        'type' => $type ? Sheet::SINGLE_TYPE : Sheet::ROLL_PAPER_TYPE,
        'name' => $faker->title,
        'height' => $height,
        'width' => $faker->numberBetween(150, 1500),
        'properties' => $properties,
    ];
});
