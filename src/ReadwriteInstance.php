<?php

namespace Dios\System\Multicasting;

/**
 * Implements getting and setting instances of entities.
 */
trait ReadwriteInstance
{
    /**
     * Returns an instance of the instance of the model from the attribute.
     *
     * @return EntityHandlerInterface|null
     */
    public function getInstanceAttribute()
    {
        return $this->getInstance();
    }

    /**
     * Sets values to the instance or the attribute.
     *
     * @param array $values
     */
    public function setInstanceAttribute(array $values)
    {
        $instance = $this->getInstance();

        if ($instance) {
            $this->fillFromArray($values);
        } else {
            $this->{$this->propertyOfEntityValues} = $values;
        }
    }
}
