<?php

namespace Dios\System\Multicasting\Interfaces;

/**
 * Uses for initialization an instance by using a single value.
 */
interface SingleValueEntity
{
    /**
     * Fills an instance of the class with a value.
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
