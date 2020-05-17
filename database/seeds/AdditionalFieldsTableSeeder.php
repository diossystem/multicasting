<?php

use Illuminate\Database\Seeder;
use Tests\Models\AdditionalField;
use Tests\Models\Page;

class AdditionalFieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createMap();

        $images = factory(AdditionalField::class)->create([
            'code_name' => 'images',
            'title' => 'Images',
            'type' => 'files',
        ]);

        $contacts = factory(AdditionalField::class)->create([
            'code_name' => 'contacts',
            'title' => 'Contacts',
        ]);

        $sources = factory(AdditionalField::class)->create([
            'code_name' => 'sources',
            'title' => 'Sources of a content',
        ]);

        $recommendations = factory(AdditionalField::class)->create([
            'code_name' => 'recommendations',
            'title' => 'Page with services',
            'type' => 'local_pages',
        ]);

        factory(AdditionalField::class, 5)
            ->create([
                'active' => false,
                'description' => 'inactive',
            ])
            ->each(function ($af) {
                $af->pages()->save(factory(Page::class)->make());
            })
        ;
    }

    public function createMap(): AdditionalField
    {
        $map = factory(AdditionalField::class)->create([
            'code_name' => 'map',
            'title' => 'Map',
            'type' => 'map',
        ]);

        $map->pages()->attach(
            factory(Page::class)->create(['title' => 'Page with map'])->id,
            [
                'values' => [
                    'title' => 'This is a map',
                    'address' => '210000, Vitebsk, Belarus',
                    'phone' => '80212000000',
                    'script' => 'Here must be Javascript that contains the map.',
                    'link' => 'Link to the map',
                    'image_url' => 'URL to an image contains the map'
                ],
            ]
        );

        return $map;
    }
}
