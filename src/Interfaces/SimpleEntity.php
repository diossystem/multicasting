<?php

namespace Dios\System\Multicasting\Interfaces;

/**
 * Uses for initialization an instance by using an array with values.
 */
interface SimpleEntity extends MulticastingEntity
{
    /**
     * Initializes an instance of the class.
     *
     * @param array $array An array with values of the attribute.
     */
    public function __construct(array $array);
}
