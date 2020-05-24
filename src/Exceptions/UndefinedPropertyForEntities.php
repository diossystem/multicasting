<?php

namespace Dios\System\Multicasting\Exceptions;

/**
 * The exception for an undefined property for entities.
 */
class UndefinedPropertyForEntities extends Exception
{
    /**
     * The message of the exception.
     *
     * @var string
     */
    protected $message = 'The variable with a property for entities was not assigned.';
}
