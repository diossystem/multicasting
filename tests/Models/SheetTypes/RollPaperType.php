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
}
