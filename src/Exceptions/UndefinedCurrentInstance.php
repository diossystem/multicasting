<?php

namespace Dios\System\Multicasting\Exceptions;

/**
 * The exception for an undefined source of a type.
 */
class UndefinedCurrentInstance extends \Exception
{
    /**
     * The message of the exception.
     *
     * @var string
     */
    protected $message = 'The current instance is undefined.';
}
