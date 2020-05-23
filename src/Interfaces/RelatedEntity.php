<?php

namespace Dios\System\Multicasting\Interfaces;

use Illuminate\Database\Eloquent\Model;

/**
 * It used for initialization a reference to the instance of the model.
 * The instance of the class must updates the instance of the model
 * with filled data.
 */
interface RelatedEntity extends MulticastingEntity
{
    /**
     * Initializes an instance of a class.
     *
     * @param Model $instance
     * @param string $attribute An attribute of the model.
     */
    public function __construct(Model &$instance, string $attribute);

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
     * Returns an attribute name.
     *
     * @return string
     */
    public function getAttribteName(): string;

    /**
     * Saves an instance with the current values.
     */
    public function save();
}
