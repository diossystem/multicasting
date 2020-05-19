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

    public function testGetInstance()
    {
        /** @var Page $pageWithAF **/
        $pageWithAF = $this->getFirstPageOfAF('images');

        $this->assertInstanceOf(SimpleEntity::class, $pageWithAF->pivot->getInstance());
    }

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
        /** @var Page $pageWithAF **/
        $pageWithAF = $this->getFirstPageOfAF($type);

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

    public function testMapEntity()
    {
        /** @var Page $pageWithMap **/
        $pageWithMap = $this->getFirstPageOfAF('map');

        /** @var SimpleEntity $instance **/
        $instance = $pageWithMap->pivot->instance;

        $this->assertInstanceOf(\Tests\Models\AdditionalFieldHandlers\Map::class, $instance);

        $script = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4845114.511799304!2d23.49280518303527!3d53.633088464731756!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46da2584e2ad4881%3A0xa1d181ec8c10!2z0JHQtdC70LDRgNGD0YHRjA!5e0!3m2!1sru!2sby!4v1589822749261!5m2!1sru!2sby" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';
        $image = 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Strusta_Lake_-_Panorama.jpg/1280px-Strusta_Lake_-_Panorama.jpg';

        $this->assertEquals('This is a map', $instance->getTitle());
        $this->assertEquals('210000, Vitebsk, Belarus', $instance->getAddress());
        $this->assertEquals('80212000000', $instance->getPhone());
        $this->assertEquals([], $instance->getPhones());
        $this->assertEquals($script, $instance->getScript());
        $this->assertEquals('https://goo.gl/maps/8bH1vbYgG6D48qH86', $instance->getUrl());
        $this->assertEquals($image, $instance->getUrlToImage());
    }

    public function testImages()
    {
        /** @var Page $pageWithImages **/
        $pageWithImages = $this->getFirstPageOfAF('images');

        /** @var SimpleEntity $instance **/
        $instance = $pageWithImages->pivot->instance;

        $this->assertInstanceOf(\Tests\Models\AdditionalFieldHandlers\Images::class, $instance);

        $this->assertTrue($instance->isActive());
        $this->assertEquals('list', $instance->getVisualizationType());
        $this->assertSame(5, $instance->getNumberOfVisibleImages());

        /** @var FileCollection| $list **/
        $list = $instance->getList();

        $this->assertCount(7, $list);
        $this->assertInstanceOf(\Tests\Models\AdditionalFieldHandlers\FileCollection::class, $list);
        $this->assertInstanceOf(\Tests\Models\AdditionalFieldHandlers\ImageCollection::class, $list);

        /** @var \Tests\Models\AdditionalFieldHandlers\Image $image **/
        $image = $list[0];

        $this->assertSame(1, $image->getId());
        $this->assertEquals('/link/to/download', $image->getLink());
        $this->assertEquals('/link/to/image', $image->getUrl());
        $this->assertEquals('Title', $image->getTitle());
        $this->assertEquals('Description', $image->getAlt());
        $this->assertEquals('watermark', $image->getDefaultSourceType());

        $this->assertCount(7, $list->getUrls());

        /** @var array|string[] $urls **/
        $urls = $list->getUrls();
        $this->assertEquals('/link/to/image', $urls[0]);
    }

    public function testSetInstanceAttribute()
    {
        /** @var Page $pageWithMap **/
        $pageWithMap = $this->getFirstPageOfAF('map');

        /** @var SimpleEntity $instance **/
        $instance = $pageWithMap->pivot->instance;

        $instance->setTitle('New title');
        $pageWithMap->pivot->instance = $instance->toArray();

        $this->assertEquals($instance->toArray(), $pageWithMap->pivot->instance->toArray());
        $this->assertEquals($instance->toArray(), $pageWithMap->pivot->values);

        $pageWithMap->pivot->save();

        /** @var Page $pageWithMap **/
        $pageWithMapAfterSaving = $this->getFirstPageOfAF('map');

        $this->assertEquals('New title', $pageWithMapAfterSaving->pivot->instance->toArray()['title']);
    }

    /**
     * Returns a first page with AF by the given type of an additional field.
     *
     * @param  string $type A type of an additional field.
     * @return Page
     */
    protected function getFirstPageOfAF(string $type)
    {
        /** @var AdditionalField $af **/
        $af = AdditionalField::type($type)->first();

        return $af->pages()->first();
    }
}
