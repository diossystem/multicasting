<?php

namespace Tests\Models\AdditionalFieldHandlers;

/**
 * Contains information about an image.
 */
class Image extends File
{
    protected $title;

    protected $alt;

    protected $defaultSourceType;

    function __construct(array $array)
    {
        parent::__construct($array);

        $this->title = $array['title'];
        $this->alt = $array['alt'];
        $this->defaultSourceType = $array['source_type'];
    }

    /**
     * Sets a title.
     *
     * @param string|null $title
     */
    public function setTitle(string $title = null)
    {
        $this->title = $title;
    }

    /**
     * Returns a title.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets an Alt.
     *
     * @param string|null $alt
     */
    public function setAlt(string $alt = null)
    {
        $this->alt = $alt;
    }

    /**
     * Returns an Alt.
     *
     * @return string
     */
    public function getAlt(): string
    {
        return $this->alt ?? '';
    }

    /**
     * Sets a default source type.
     *
     * @param string|null $type
     */
    public function setDefaultSourceType(string $type = null)
    {
        $this->defaultSourceType = $type;
    }

    /**
     * Returns a default source type.
     *
     * @return string|null
     */
    public function getDefaultSourceType()
    {
        return $this->defaultSourceType;
    }

    /**
     * Returns an URL to image.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return '/link/to/image';
    }

    /**
     * Returns an array with data of the instance.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'alt' => $this->getAlt(),
            'source_type' => $this->getDefaultSourceType(),
        ];
    }
}
