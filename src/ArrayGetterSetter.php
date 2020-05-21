<?php

namespace Dios\System\Multicasting;

/**
 * Getter and setter for entities with arrays.
 */
trait ArrayGetterSetter
{
    /**
     * Gets values from the instance or the attribute ($propertyOfEntityValues).
     *
     * @return array
     */
    public function getValuesFromInstance(): array
    {
        /** @var MulticastingEntity|null $instance **/
        $instance = $this->getInstance();

        if ($instance) {
            $values = $instance->toArray();
        } elseif (isset($this->{$this->propertyOfEntityValues}) && is_array($this->{$this->propertyOfEntityValues})) {
            $values = $this->{$this->propertyOfEntityValues};
        }

        return $values ?? [];
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
