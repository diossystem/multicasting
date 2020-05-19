<?php

namespace Tests\Models\AdditionalFieldHandlers;

/**
 * Contains a list with images.
 */
class ImageCollection extends FileCollection
{
    public function __construct(array $array)
    {
        $this->position = 0;

        $this->files = array_map(function ($item) {
            return new Image($item);
        }, $array);
    }

    /**
     * An array with files.
     *
     * @var array|Image[]
     */
    protected $files;

    /**
     * Returns a list of URL to images.
     *
     * @return array|string[]
     */
    public function getUrls(): array
    {
        return array_map(function ($item) {
            return $item->getUrl();
        }, $this->files);
    }
}
