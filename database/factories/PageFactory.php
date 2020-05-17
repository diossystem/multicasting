<?php

use Faker\Generator as Faker;
use Tests\Models\Page;
use Illuminate\Support\Str;

$factory->define(Page::class, function (Faker $faker) {
    $content = $faker->realText(256);

    return [
        'state' => 'published',
        'published_at' => now(),
        'slug' => $faker->unique()->slug,
        'title' => $faker->sentence,
        'description' => Str::limit($content, 160),
        'content' => $content,
        'description_tag' => Str::limit($content, 160),
        'keywords_tag' => '',
        'template_id' => null,
    ];
});
