<?php

namespace Dios\System\Multicasting;

use Dios\System\Multicasting\Interfaces\MulticastingEntity;
use Dios\System\Multicasting\Interfaces\ArrayEntity;

/**
 * Getter and setter for entities with arrays.
 */
trait ArrayGetterSetter
{
    /**
     * Gets values from the instance or the attribute ($propertyForEntity).
     *
     * @return array
     */
    public function getValuesFromInstance(): array
    {
        /** @var MulticastingEntity|ArrayEntity|null $instance **/
        $instance = $this->getInstance();

        if ($instance) {
            $this->throwExceptionWhenInvalidInterface($instance);
            $values = $instance->toArray();
        } elseif (isset($this->{$this->propertyForEntity}) && is_array($this->{$this->propertyForEntity})) {
            $values = $this->{$this->propertyForEntity};
        }

        return $values ?? [];
    }

    /**
     * Sets values to the instance and the attribute ($propertyForEntity).
     *
     * @param array $values
     */
    public function setValuesToInstance(array $values)
    {
        /** @var MulticastingEntity|ArrayEntity|null $instance **/
        $instance = $this->getInstance();

        // Fills and gets data to update the current value of the property
        // with normalized data.
        if ($instance) {
            $this->throwExceptionWhenInvalidInterface($instance);
            $instance->fillFromArray($values);
            $values = $instance->toArray();
        }

        $this->{$this->propertyForEntity} = $values;
    }

    /**
     * Throws the exception when it is invalid interface.
     *
     * @param MulticastingEntity $instance
     *
     * @throws Exception
     */
    public function throwExceptionWhenInvalidInterface(MulticastingEntity $instance): void
    {
        if (! ($instance instanceof ArrayEntity)) {
            throw new \Exception('The given instance is invalid interface. The instance must be implements ' . ArrayEntity::class);
        }
    }
}
