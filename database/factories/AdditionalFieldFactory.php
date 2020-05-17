<?php

use Faker\Generator as Faker;
use Tests\Models\AdditionalField;
use Illuminate\Support\Str;

$factory->define(AdditionalField::class, function (Faker $faker) {
    return [
        'code_name' => Str::snake($faker->unique()->words(3, true)),
        'title' => $faker->unique()->sentence,
        'description' => $faker->realText(1000),
        'type' => 'custom',
        'active' => true,
    ];
});
