<?php

namespace Dios\System\Multicasting\Exceptions;

/**
 * The exception for an undefined entity type mapping.
 */
class UndefinedEntityTypeMapping extends \Exception
{
    /**
     * The message of the exception.
     *
     * @var string
     */
    protected $message = 'The entity type mapping is undefined.';
}
