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
}
