<?php

namespace Tests\Models\AdditionalFieldHandlers;

/**
 * Contains a list with files.
 */
class FileCollection implements \Countable, \Iterator, \ArrayAccess
{
    /**
     * An array with files.
     *
     * @var array|File[]
     */
    protected $files;

    /**
     * A current position.
     *
     * @var int
     */
    protected $position;

    function __construct(array $array = [])
    {
        $this->position = 0;

        $this->files = array_map(function ($item) {
            return new File($item);
        }, $array);


    }

    public function count(): int
    {
        return count($this->files);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->files[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->files[$this->position]);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->files[] = $value;
        } else {
            $this->files[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->files[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->files[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->files[$offset]) ? $this->files[$offset] : null;
    }

    /**
     * Returns IDs of files.
     *
     * @return array|int[]
     */
    public function getIds(): array
    {
        return array_map(function ($file) {
            return $file->getId();
        });
    }
    
    /**
     * Returns an array with data of the instance.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_map(function ($file) {
            return $file->toArray();
        }, $this->files);
    }
}
