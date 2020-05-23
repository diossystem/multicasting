<?php

namespace Dios\System\Multicasting\Interfaces;

/**
 * Is used for initialization an instance that uses a single value.
 */
interface SingleValueEntity extends MulticastingEntity
{
    /**
     * Fills an instance of a class with a value.
     * May assign default value or throw an exception, also may return a state
     * of the assigment.
     *
     * @param  mixed  $array
     */
    public function setValue($value);

    /**
     * Returns a value of the attribute.
     *
     * @return mixed
     */
    public function getValue();
}
