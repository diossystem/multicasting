<?php

namespace Dios\System\Multicasting\Interfaces;

use Illuminate\Database\Eloquent\Model;

/**
 * Uses for initialization an instance of the model
 * and reads an attribute of the instance.
 */
interface EntityWithModel extends MulticastingEntity
{
    /**
     * Initializes an instance from the model and its attribute.
     *
     * @param Model  $instance
     * @param string $attribute An attribute of the model.
     */
    public function __construct(Model $instance, string $attribute);

    /**
     * Returns the current model of the instance.
     *
     * @return Model
     */
    public function getModel(): Model;

    /**
     * Returns an attribute name.
     *
     * @return string
     */
    public function getAttribteName(): string;
}
