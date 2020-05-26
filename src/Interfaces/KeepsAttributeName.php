<?php

namespace Dios\System\Multicasting\Interfaces;

/**
 * The interface to implement a class that keeps an attribute name.
 */
interface KeepsAttributeName extends MulticastingEntity
{
    /**
     * Sets an attribute name where stores values of the entity.
     *
     * @param string $type
     */
    public function setAttributeName(string $name);

    /**
     * Returns an attribute name.
     *
     * @return string
     */
    public function getAttributeName(): string;
}
