<?php

namespace Tests\Models\AdditionalFieldHandlers;

use Dios\System\Multicasting\Interfaces\SimpleEntity;

/**
 * Keeps data of images.
 */
class Images implements SimpleEntity
{
    protected $list;

    protected $active;

    protected $visualizationType;

    protected $numberOfVisibleImages;

    function __construct(array $array = null)
    {
        $this->fillFromArray($array ?? []);
    }

    /**
     * Fills an instance of the class by with values.
     *
     * @param  array  $values
     */
    public function fillFromArray(array $values)
    {
        $this->list = $values['list'] ?? [];
        $this->active = $values['active'] ?? false;
        $this->numberOfVisibleImages = $values['number_of_visible_images'] ?? 0;
        $this->visualizationType = $values['visualization_type'] ?? 'list';
    }

    /**
     * Returns a state of activity.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Returns a visualization type.
     *
     * @return string|null
     */
    public function getVisualizationType(): string
    {
        return $this->visualizationType;
    }

    /**
     * Returns a number of visible images.
     *
     * @return int
     */
    public function getNumberOfVisibleImages(): int
    {
        return $this->numberOfVisibleImages;
    }

    /**
     * Returns a list of images.
     *
     * @return array
     */
    public function getList(): array
    {
        return $this->list;
    }

    /**
     * Returns values of the instance in the form of the array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'list' => $this->list,
            'active' => $this->active,
            'number_of_visible_images' => $this->numberOfVisibleImages,
            'visualization_type' => $this->visualizationType,
        ];
    }
}
