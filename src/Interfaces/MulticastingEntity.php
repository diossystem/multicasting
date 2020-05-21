<?php

namespace Dios\System\Multicasting\Interfaces;

/**
 * The common interface for other interfaces to implement
 * interfaces of multicasting entities.
 */
interface MulticastingEntity
{
    /**
     * Fills an instance of the class with values.
     * May assign default values or throw an exception, also may return a state
     * of the assigment.
     *
     * @param  array  $array
     */
    public function fillFromArray(array $array);

    /**
     * Returns an array with values of the attribute.
     *
     * @return array
     */
    public function toArray(): array;
}
