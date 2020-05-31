<?php

namespace Tests\Models\SheetTypes;

use Tests\Models\Sheet;
use Illuminate\Database\Eloquent\Model;
use Dios\System\Multicasting\Interfaces\EntityWithModel;

class RollPaperType implements EntityWithModel
{
    /**
     * An instance of the model.
     *
     * @return Model|Sheet
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Returns the current model of the instance.
     *
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
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
}
