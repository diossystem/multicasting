<?php

namespace Tests;

use AdditionalFieldsTableSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Models\AdditionalField;
use Dios\System\Multicasting\Interfaces\SimpleEntity;
use Tests\TestCase;

class AdditionalFieldTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->loadBaseMigrations();
        $this->seed(AdditionalFieldsTableSeeder::class);
    }

    public function testStructure()
    {
        /** @var array|string[] $attributes **/
        $attributes = array_keys(AdditionalField::first()->getOriginal());

        $this->assertEquals([
            'id',
            'code_name',
            'title',
            'description',
            'type',
            'active',
        ], $attributes);
    }

    // public function testGetInstance()
    // {
    //     // code...
    // }

    /**
     * @param  string     $type           A type of additional field.
     * @param  bool       $instanceExists
     * @param  string     $instanceClass
     * @param  array|null $values         Values of the instance.
     *
     * @dataProvider getInstanceAttributeProvider
     */
    public function testGetInstanceAttribute(string $type, bool $instanceExists, string $instanceClass = null, array $values = null)
    {
        /** @var AdditionalField $af **/
        $af = AdditionalField::type($type)->first();

        /** @var Page $pageWithAF **/
        $pageWithAF = $af->pages()->first();

        /** @var SimpleEntity|null $instance **/
        $instance = $pageWithAF->pivot->instance;

        if ($instanceExists) {
            $this->assertInstanceOf($instanceClass, $instance);
            $this->assertEquals($values, $instance->toArray());
        } else {
            $this->assertNull($instance);
        }
    }

    public function getInstanceAttributeProvider(): array
    {
        return [
            'map' => [
                'map',
                true,
                SimpleEntity::class,
                [
                   'title' => 'This is a map',
                   'address' => '210000, Vitebsk, Belarus',
                   'phone' => '80212000000',
                   'phones' => [],
                   'script' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4845114.511799304!2d23.49280518303527!3d53.633088464731756!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46da2584e2ad4881%3A0xa1d181ec8c10!2z0JHQtdC70LDRgNGD0YHRjA!5e0!3m2!1sru!2sby!4v1589822749261!5m2!1sru!2sby" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>',
                   'url' => 'https://goo.gl/maps/8bH1vbYgG6D48qH86',
                   'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Strusta_Lake_-_Panorama.jpg/1280px-Strusta_Lake_-_Panorama.jpg'
               ],
           ],
           'map_other_class' => [
                'map',
                true,
                \Tests\Models\AdditionalFieldHandlers\Map::class,
                [
                    'title' => 'This is a map',
                    'address' => '210000, Vitebsk, Belarus',
                    'phone' => '80212000000',
                    'phones' => [],
                    'script' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4845114.511799304!2d23.49280518303527!3d53.633088464731756!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46da2584e2ad4881%3A0xa1d181ec8c10!2z0JHQtdC70LDRgNGD0YHRjA!5e0!3m2!1sru!2sby!4v1589822749261!5m2!1sru!2sby" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>',
                    'url' => 'https://goo.gl/maps/8bH1vbYgG6D48qH86',
                    'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Strusta_Lake_-_Panorama.jpg/1280px-Strusta_Lake_-_Panorama.jpg'
                ],
            ],
            'images' => [
                'images',
                true,
                \Tests\Models\AdditionalFieldHandlers\Images::class,
                [
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
            ],
            'default' => [
                'custom',
                true,
                \Tests\Models\AdditionalFieldHandlers\DefaultHandler::class,
                [
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
        ];
    }
}
