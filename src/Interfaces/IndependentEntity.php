<?php

namespace Dios\System\Multicasting\Interfaces;

/**
 * The interface to implement interfaces of multicasting entities
 * that do not use any value and a base property.
 */
interface IndependentEntity extends MulticastingEntity
{
    /**
     * Initializes an instance of a class without parameters.
     */
    public function __construct();
}
