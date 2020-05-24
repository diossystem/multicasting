<?php

namespace Dios\System\Multicasting\Exceptions;

/**
 * The exception for an undefined source of a type.
 */
class UndefinedSourceOfType extends Exception
{
    /**
     * The message of the exception.
     *
     * @var string
     */
    protected $message = 'The variable with a source of a type was not assigned.';
}
