<?php

namespace Dios\System\Multicasting;

use Dios\System\Multicasting\Interfaces\MulticastingEntity;
use Dios\System\Multicasting\Interfaces\SingleValueEntity;
use Dios\System\Multicasting\Exceptions\InvalidTypeOfInstance;

/**
 * Getter and setter for entities with arrays.
 */
trait SingleValueGetterSetter
{
    /**
     * Gets a value from the instance or the attribute ($propertyForEntity).
     *
     * @return mixed|null
     */
    public function getValueFromInstance()
    {
        $this->throwExceptionWhenUndefinedPropertyForEntities();

        /** @var MulticastingEntity|SingleValueEntity|null $instance **/
        $instance = $this->getInstance();

        if ($instance) {
            $this->throwExceptionWhenInvalidInterface($instance);
            $value = $instance->getValue();
        } elseif (isset($this->{$this->propertyForEntity})) {
            $value = $this->{$this->propertyForEntity};
        }

        return $value ?? null;
    }

    /**
     * Sets a value to the instance and the attribute ($propertyForEntity).
     *
     * @param mixed $value
     */
    public function setValueToInstance($value)
    {
        /** @var MulticastingEntity|SingleValueEntity|null $instance **/
        $instance = $this->getInstance();

        // Fills and gets data to update the current value of the property
        // with normalized data.
        if ($instance) {
            $this->throwExceptionWhenInvalidInterface($instance);
            $instance->setValue($value);
            $value = $instance->getValue();
        }

        $this->throwExceptionWhenUndefinedPropertyForEntities();

        $this->{$this->propertyForEntity} = $value;
    }

    /**
     * Throws the exception when it is invalid interface.
     *
     * @param MulticastingEntity $instance
     *
     * @throws InvalidTypeOfInstance
     */
    public function throwExceptionWhenInvalidInterface(MulticastingEntity $instance): void
    {
        if (! ($instance instanceof SingleValueEntity)) {
            throw new InvalidTypeOfInstance(SingleValueEntity::class);
        }
    }
}
