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
     * Sets values to the instance and the attribute ($propertyOfEntityValues).
     *
     * @param array $values
     */
    public function setInstanceAttribute(array $values)
    {
        $instance = $this->getInstance();

        // Fills and gets data to update the current value of the property
        // with normalized data.
        if ($instance) {
            $instance->fillFromArray($values);
            $values = $instance->toArray();
        }

        $this->{$this->propertyOfEntityValues} = $values;
    }
}
