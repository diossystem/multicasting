<?php

namespace Dios\System\Multicasting\Interfaces;

use Illuminate\Database\Eloquent\Model;

/**
 * Uses for initialization an instance of the model
 * and reads an attribute of the instance.
 */
interface InstanceFromModel extends MulticastingEntity
{
    /**
     * Initializes an instance from the model and its attribute.
     *
     * @param Model  $instance
     * @param string $attribute An attribute of the model.
     */
    public function __construct(Model $instance, string $attribute);
}
