<?php

namespace Dios\System\Multicasting\Exceptions;

/**
 * The exception for an invalid type of instance.
 */
class InvalidTypeOfInstance extends \Exception
{
    /**
     * The message of the exception.
     *
     * @var string
     */
    protected $message = 'The given instance is invalid interface.';

    /**
     * A base class for the message of the exception.
     *
     * @var string|null
     */
    protected $className;

    /**
     * Initializes an instance of the exception with a base interface or class.
     *
     * @param string|null $className
     */
    public function __construct(string $className = null)
    {
        $this->className = $className;
    
        if (isset($this->className)) {
            $this->message .= ' The instance must be implements ' . $this->className;
        }            
    }

    /**
     * Returns a class name to implement.
     *
     * @return string|null
     */
    public function getClassName()
    {
        return $this->className;
    }
}
