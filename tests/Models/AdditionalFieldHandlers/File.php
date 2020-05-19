<?php

namespace Tests\Models\AdditionalFieldHandlers;

/**
 * Contains information about a file.
 */
class File
{
    /**
     * An ID of the file.
     *
     * @var int
     */
    protected $id;

    function __construct(array $array)
    {
        $this->id = $array['id'];
    }

    /**
     * Returns an ID of the file.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getLink()
    {
        return '/link/to/download';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'link' => '/link/to/download',
        ];
    }
}
