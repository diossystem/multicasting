<?php

namespace Dios\System\Multicasting\Interfaces;

/**
 * The common interface for other interfaces to implement
 * interfaces of multicasting entities.
 */
interface MulticastingEntity
{
    /**
     * Fills an instance of the class by with values.
     * May assign default values or throw an exceptions, also may return a state
     * of the operation.
     *
     * @param  array  $array
     */
    public function fillFromArray(array $array);

    /**
     * Returns an array with values of the attribute.
     * The result may be used to save in the database.
     *
     * @return array
     */
    public function toArray(): array;
}
