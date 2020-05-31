<?php

namespace Dios\System\Multicasting;

use Dios\System\Multicasting\Interfaces\MulticastingEntity;

/**
 * Implements getting and setting instances of entities.
 * 
 * @mixin AttributeMulticasting
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
    public function setInstanceAttribute(MulticastingEntity $instance): bool
    {
        return $this->updateInstance($instance, false);
    }
}
