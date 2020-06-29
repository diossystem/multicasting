<?php

namespace Tests\Models\RelatedSheetTypes;

use Tests\Models\RelatedSheet;
use Illuminate\Database\Eloquent\Model;
use Dios\System\Multicasting\Interfaces\ArrayEntity;
use Dios\System\Multicasting\Interfaces\RelatedEntity;

class RollPaperType implements RelatedEntity, ArrayEntity
{
    /**
     * An instance of the model.
     *
     * @return Model|RelatedSheet
     */
    protected $model;

    public function __construct(Model &$model)
    {
        $this->model = $model;

        $this->height = $this->model->height;
        $this->width = $this->model->width;
        $this->fillFromArray($model->properties);
    }

    /**
     * Fills an instance of the class with values from the array.
     *
     * @param array|null $array
     */
    public function fillFromArray(array $array = null)
    {
        $this->setMarginsFromArray($array);

        if (isset($array['width'])) {
            $this->width = (int) $array['width'];
        }

        if (isset($array['height'])) {
            $this->height = (int) $array['height'];
        }
    }

    /**
     * Sets margins from the array.
     *
     * @param  array|null $array
     * @return void
     */
    public function setMarginsFromArray(array $array = null)
    {
        $this->topMargin    = $array['margin_top'] ?? 0;
        $this->bottomMargin = $array['margin_bottom'] ?? 0;
        $this->leftMargin   = $array['margin_left'] ?? 0;
        $this->rightMargin  = $array['margin_right'] ?? 0;
    }

    /**
     * Returns the current model of the instance.
     *
     * @return Model|RelatedSheet
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Returns a reference to the current model.
     *
     * @return Model|RelatedSheet
     */
    public function &getReference(): Model
    {
        return $this->model;
    }

    /**
     * Saves an instance with the current values.
     */
    public function save()
    {
        $this->model->save();
    }

    /**
     * Returns a height of the model.
     *
     * @return int
     */
    public function getHeight(): int
    {
        return $this->model->height;
    }

    /**
     * Returns a width of the model.
     *
     * @return int
     */
    public function getWidth(): int
    {
        return $this->model->width;
    }

    /**
     * Returns an available height.
     *
     * @return int
     */
    public function getAvailableHeight(): int
    {
        return $this->getHeight() - $this->getTopMargin() - $this->getBottomMargin();
    }

    /**
     * Returns an available width.
     *
     * @return int
     */
    public function getAvailableWidth(): int
    {
        return $this->getWidth() - $this->getLeftMargin() - $this->getRightMargin();
    }

    /**
     * Returns a top margin of the model.
     *
     * @return int
     */
    public function getTopMargin(): int
    {
        return $this->model->properties['margin_top'] ?? 0;
    }

    /**
     * Returns a bottom margin of the model.
     *
     * @return int
     */
    public function getBottomMargin(): int
    {
        return $this->model->properties['margin_bottom'] ?? 0;
    }

    /**
     * Returns a left margin of the model.
     *
     * @return int
     */
    public function getLeftMargin(): int
    {
        return $this->model->properties['margin_left'] ?? 0;
    }

    /**
     * Returns a right margin of the model.
     *
     * @return int
     */
    public function getRightMargin(): int
    {
        return $this->model->properties['margin_right'] ?? 0;
    }

    /**
     * Returns an indent.
     *
     * @return int
     */
    public function getIndent(): int
    {
        return $this->model->properties['indent'] ?? 0;
    }

    /**
     * Checks whether the sheet can contain a product that have the given size.
     *
     * @param  int  $height
     * @param  int  $width
     * @return bool
     */
    public function canContain(int $height, int $width): bool
    {
        return $height >= 1
            && $width >= 1
            && $this->getAvailableHeight() >= $height
            && $this->getAvailableWidth() >= $width;
    }

    /**
     * Returns an array with margins.
     *
     * @return array
     */
    public function getArrayWithMargins(): array
    {
        return [
            'margin_top' => $this->getTopMargin(),
            'margin_bottom' => $this->getBottomMargin(),
            'margin_left' => $this->getLeftMargin(),
            'margin_right' => $this->getRightMargin()
        ];
    }

    /**
     * Returns an array of the instance.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'width' => $this->getWidth(),
            'height' => $this->getHeight(),
            'available_height' => $this->getAvailableHeight(),
            'available_width' => $this->getAvailableWidth(),
        ] + $this->getArrayWithMargins();
    }
}
