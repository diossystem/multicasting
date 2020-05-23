<?php

namespace Dios\System\Multicasting\Interfaces;

/**
 * The interface to implement interfaces of multicasting entities
 * that uses an array in the base property.
 */
interface ArrayEntity extends MulticastingEntity
{
    /**
     * Fills an instance of a class with values from an array.
     * May assign default values or throw an exception, also may return a state
     * of the assigment.
     *
     * @param array $array
     */
    public function fillFromArray(array $array);

    /**
     * Returns an array with values for the attribute.
     *
     * @return array
     */
    public function toArray(): array;
}
