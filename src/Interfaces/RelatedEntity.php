<?php

namespace Dios\System\Multicasting\Interfaces;

use Illuminate\Database\Eloquent\Model;

/**
 * Uses for initialization a reference to the instance of the model.
 * The instance of the class must updates the instance of the model
 * with filled data.
 */
interface RelatedEntity extends MulticastingEntity
{
    /**
     * Initializes an instance of the class.
     *
     * @param Model $instance
     */
    public function __construct(Model &$instance);

    /**
     * Saves an instance with the current values.
     */
    public function save();
}
