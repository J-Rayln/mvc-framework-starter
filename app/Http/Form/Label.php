<?php

namespace JonathanRayln\UdemyClone\Http\Form;

use JonathanRayln\UdemyClone\Models\BaseModel;

class Label
{
    protected BaseModel $model;
    protected string $attribute;
    protected bool $hidden;

    /**
     * @param BaseModel $model
     * @param string    $attribute                                  Attribute this label is for.
     * @param bool      $hidden                                     Set to true to apply a 'visually-hidden' class
     *                                                              to the label.
     */
    public function __construct(BaseModel $model, string $attribute, bool $hidden = false)
    {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->hidden = $hidden;
    }

    public function __toString(): string
    {
        return sprintf(
            '<label for="%s" class="form-label%s">%s</label>' . PHP_EOL,
            $this->attribute,
            $this->hidden === true ? ' visually-hidden' : '',
            $this->model->getLabel($this->attribute)
        );
    }
}