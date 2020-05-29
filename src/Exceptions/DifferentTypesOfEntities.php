<?php

namespace Dios\System\Multicasting\Exceptions;

/**
 * The exception for different types of entities.
 */
class DifferentTypesOfEntities extends \Exception
{
    /**
     * The message of the exception.
     *
     * @var string
     */
    protected $message = 'The given instance has another type in comparison with the current instance.';
}
