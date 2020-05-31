<?php

namespace Tests\Models\RelatedSheetTypes;

use Tests\Models\RelatedSheet;
use Illuminate\Database\Eloquent\Model;
use Dios\System\Multicasting\Interfaces\ArrayEntity;
use Dios\System\Multicasting\Interfaces\RelatedEntity;
use Dios\System\Multicasting\Interfaces\KeepsEntityType;

class SingleType implements RelatedEntity, ArrayEntity, KeepsEntityType
{
    /**
     * An instance of the model.
     *
     * @return Model|RelatedSheet
     */
    protected $model;

    protected $height;

    protected $width;

    protected $topMargin;

    protected $bottomMargin;

    protected $leftMargin;

    protected $rightMargin;

    protected $entityType;

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
        $this->syncValues();

        return $this->model;
    }

    /**
     * Returns a reference to the current model.
     *
     * @return Model|RelatedSheet
     */
    public function &getReference(): Model
    {
        $this->syncValues();

        return $this->model;
    }

    /**
     * Syncs the current values to the model.
     *
     * @return void
     */
    public function syncValues()
    {
        $this->model->height = $this->getHeight();
        $this->model->width = $this->getWidth();
        $this->model->properties = $this->getArrayWithMargins();
    }

    /**
     * Saves an instance with the current values.
     */
    public function save()
    {
        $this->syncValues();
        $this->model->save();
    }

    /**
     * Sets an entity type.
     *
     * @param string $type
     */
    public function setEntityType(string $type)
    {
        $this->entityType = $type;
    }

    /**
     * Returns an entity type.
     *
     * @return string
     */
    public function getEntityType(): string
    {
        return $this->entityType;
    }
    
    /**
     * Sets the height to the model.
     *
     * @param  int  $height
     * @return void
     */
    public function setHeight(int $height)
    {
        $this->height = $height;
    }

    /**
     * Returns a height of the model.
     *
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * Sets the width to the model.
     *
     * @param  int  $width
     * @return void
     */
    public function setWidth(int $width)
    {
        $this->width = $width;
    }

    /**
     * Returns a width of the model.
     *
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
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
     * Sets the top margin to the model.
     *
     * @param  int   $margin
     * @return void
     */
    public function setTopMargin(int $margin)
    {
        $this->topMargin = $margin;
    }

    /**
     * Returns a top margin of the model.
     *
     * @return int
     */
    public function getTopMargin(): int
    {
        return $this->topMargin;
    }

    /**
     * Sets the bottom margin to the model.
     *
     * @param  int   $margin
     * @return void
     */
    public function setBottomMargin(int $margin)
    {
        $this->bottomMargin = $margin;
    }

    /**
     * Returns a bottom margin of the model.
     *
     * @return int
     */
    public function getBottomMargin(): int
    {
        return $this->bottomMargin;
    }

    /**
     * Sets the left margin to the model.
     *
     * @param  int   $margin
     * @return void
     */
    public function setLeftMargin(int $margin)
    {
        $this->leftMargin = $margin;
    }

    /**
     * Returns a left margin of the model.
     *
     * @return int
     */
    public function getLeftMargin(): int
    {
        return $this->leftMargin;
    }

    /**
     * Sets the right margin to the model.
     *
     * @param  int   $margin
     * @return void
     */
    public function setRightMargin(int $margin)
    {
        $this->rightMargin = $margin;
    }

    /**
     * Returns a right margin of the model.
     *
     * @return int
     */
    public function getRightMargin(): int
    {
        return $this->rightMargin;
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
            && $this->getAvailableWidth() >= $width
        ;
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
