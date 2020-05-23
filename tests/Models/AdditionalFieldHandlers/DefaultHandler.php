<?php

namespace Tests\Models\AdditionalFieldHandlers;

use Dios\System\Multicasting\Interfaces\SimpleArrayEntity;

/**
 * Keeps data of AF.
 */
class DefaultHandler implements SimpleArrayEntity
{
    protected $values;

    function __construct(array $array = null)
    {
        $this->fillFromArray($array ?? []);
    }

    /**
     * Fills an instance of the class by with values.
     *
     * @param  array  $values
     */
    public function fillFromArray(array $values)
    {
        $this->values = $values;
    }

    /**
     * Returns values of the instance in the form of the array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->values;
    }
}
