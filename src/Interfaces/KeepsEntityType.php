<?php

namespace Dios\System\Multicasting\Interfaces;

/**
 * The interface to implement a class that keeps an entity type.
 */
interface KeepsEntityType extends MulticastingEntity
{
    /**
     * Sets an entity type.
     *
     * @param string $type
     */
    public function setEntityType(string $type);

    /**
     * Returns an entity type.
     *
     * @return string
     */
    public function getEntityType(): string;
}
