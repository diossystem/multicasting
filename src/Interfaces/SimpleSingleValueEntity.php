<?php

namespace Dios\System\Multicasting\Interfaces;

/**
 * Is used for initialization an instance that uses a single value.
 */
interface SimpleSingleValueEntity extends SingleValueEntity
{
    /**
     * Initializes an instance of a class using a value of the entity.
     *
     * @param mixed|null $value 
     */
    public function __construct($value = null);
}
