<?php

namespace Dios\System\Multicasting\Interfaces;

use Illuminate\Database\Eloquent\Model;

/**
 * It used for initialization an instance of the model
 * and reads an attribute of the instance.
 */
interface EntityWithModel extends MulticastingEntity
{
    /**
     * Initializes an instance from a model.
     *
     * @param Model $instance
     */
    public function __construct(Model $instance);

    /**
     * Returns the current model of the instance.
     *
     * @return Model
     */
    public function getModel(): Model;
}
