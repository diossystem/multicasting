<?php

namespace Tests;

use Tests\Models\AdditionalFieldsOfPages;
use Tests\TestCase;

class AttributeMulticastingTest extends TestCase
{
    /**
     * @param  string $className
     * @param  array  $types
     * @return void
     *
     * @dataProvider getTypesProvider
     */
    public function testGetTypes(string $className, array $types)
    {
        /** @var AdditionalFieldsOfPages $af */
        $af = new $className;

        $this->assertCount(count($types), $af->getTypesOfEntities());
        $this->assertEquals($types, $af->getTypesOfEntities());
    }

    public function getTypesProvider(): array
    {
        return [
            [
                AdditionalFieldsOfPages::class,
                [
                    'map',
                    'images',
                ],
            ]
        ];
    }

    // TODO
    // 1. getTypes
    // 2. getTypeByKey
    // 3. getKeys and types
    // 4. get
    // 5. Test cache
    // 5. default entity handler
    // 6. Test each function.
    // 7. Test exceptions
    // 8. Test working of models and instances
    // Use empty model, full model, model with default properties
}
