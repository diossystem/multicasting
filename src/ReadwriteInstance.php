<?php

namespace Dios\System\Multicasting;

use Dios\System\Multicasting\Interfaces\MulticastingEntity;

/**
 * Implements getting and setting instances of entities.
 */
trait ReadwriteInstance
{
    /**
     * Returns an instance of the instance of the model from the attribute.
     *
     * @return MulticastingEntity|null
     */
    public function getInstanceAttribute()
    {
        return $this->getInstance();
    }

    /**
     * Updates the current instance using a given instance.
     * Both instances must be implemented using same entity.
     *
     * @param MulticastingEntity $instance
     * @param bool
     */
    public function setInstanceAttribute(MulticastingEntity $instance)
    {
        return $this->updateInstance($instance, false);
    }

    /**
     * Sets values to the instance and the attribute ($propertyOfEntityValues).
     *
     * @param array $values
     */
    public function setValuesToInstance(array $values)
    {
        /** @var MulticastingEntity|null $instance **/
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
