<?php

namespace Dios\System\Multicasting\Interfaces;

/**
 * It used for initialization an instance using an array with values.
 */
interface SimpleArrayEntity extends ArrayEntity
{
    /**
     * Initializes an instance of a class.
     *
     * @param array|null $array An array with values of the attribute.
     */
    public function __construct(array $array = null);
}
