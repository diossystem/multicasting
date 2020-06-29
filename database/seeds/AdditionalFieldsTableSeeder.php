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
        $this->createImages();
        $this->createWithoutEntity();

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
                    'phones' => [],
                    'script' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4845114.511799304!2d23.49280518303527!3d53.633088464731756!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46da2584e2ad4881%3A0xa1d181ec8c10!2z0JHQtdC70LDRgNGD0YHRjA!5e0!3m2!1sru!2sby!4v1589822749261!5m2!1sru!2sby" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>',
                    'url' => 'https://goo.gl/maps/8bH1vbYgG6D48qH86',
                    'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Strusta_Lake_-_Panorama.jpg/1280px-Strusta_Lake_-_Panorama.jpg',
                ]
            ]
        );

        return $map;
    }

    public function createImages(): AdditionalField
    {
        $images = factory(AdditionalField::class)->create([
            'code_name' => 'images',
            'title' => 'Images',
            'type' => 'images',
        ]);

        $images->pages()->attach(
            factory(Page::class)->create(['title' => 'Page with images'])->id,
            [
                'values' => [
                    'list' => [
                        [
                            'id' => 1,
                            'alt' => 'Description',
                            'title' => 'Title',
                            'source_type' => 'watermark',
                        ],
                        [
                            'id' => 2,
                            'alt' => 'Description',
                            'title' => 'Title',
                            'source_type' => 'watermark',
                        ],
                        [
                            'id' => 3,
                            'alt' => 'Description',
                            'title' => 'Title',
                            'source_type' => 'original',
                        ],
                        [
                            'id' => 4,
                            'alt' => 'Description',
                            'title' => 'Title',
                            'source_type' => 'original',
                        ],
                        [
                            'id' => 5,
                            'alt' => 'Description',
                            'title' => 'Title',
                            'source_type' => 'original',
                        ],
                        [
                            'id' => 6,
                            'alt' => 'Description',
                            'title' => 'Title',
                            'source_type' => 'original',
                        ],
                        [
                            'id' => 7,
                            'alt' => 'Description',
                            'title' => 'Title',
                            'source_type' => 'original',
                        ],
                    ],
                    'active' => true,
                    'number_of_visible_images' => 5,
                    'visualization_type' => 'list',
                ],
            ]
        );

        return $images;
    }

    public function createWithoutEntity(): AdditionalField
    {
        $withoutEntity = factory(AdditionalField::class)->create([
            'code_name' => 'without_entity',
        ]);

        $withoutEntity->pages()->attach(
            factory(Page::class)->create(['title' => 'Page with contacts'])->id,
            [
                'values' => [
                    'list' => [
                        [
                            'phone' => '12345678',
                            'contact_name' => 'Department 1',
                            'caller_name' => 'John Doe',
                        ],
                        [
                            'phone' => '12345670',
                            'contact_name' => 'Department 2',
                            'caller_name' => 'Jack Doe',
                        ],
                    ]
                ],
            ]
        );

        return $withoutEntity;
    }
}
