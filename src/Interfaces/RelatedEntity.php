<?php

namespace Dios\System\Multicasting\Interfaces;

use Illuminate\Database\Eloquent\Model;

/**
 * It used for initialization a reference to the instance of a model.
 * The instance of the class must updates the instance of the model
 * with filled data.
 */
interface RelatedEntity extends MulticastingEntity
{
    /**
     * Initializes an instance of a class.
     *
     * @param Model $instance
     */
    public function __construct(Model &$instance);

    /**
     * Returns the current model of the instance.
     *
     * @return Model
     */
    public function getModel(): Model;

    /**
     * Returns a reference to the current model.
     *
     * @return Model
     */
    public function &getReference(): Model;

    /**
     * Saves an instance with the current values.
     */
    public function save();
}
