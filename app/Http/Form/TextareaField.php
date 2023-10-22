<?php

namespace JonathanRayln\UdemyClone\Http\Form;

use JonathanRayln\UdemyClone\Models\BaseModel;

class TextareaField extends BaseField
{
    /** @var int|null Value for 'rows=' attribute. */
    public ?int $rows;

    public function __construct(BaseModel $model, string $attribute, int $rows = null)
    {
        $this->rows = $rows;
        parent::__construct($model, $attribute);
    }

    public function renderInput(): string
    {
        $rows = $this->rows ? ' rows="' . $this->rows . '"' : '';

        return sprintf(
            '<textarea name="%s" id="%s" class="form-control%s"%s%s>%s</textarea>',
            $this->attribute,
            $this->attribute,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $rows,
            $this->disabled ? ' disabled="disabled"' : '',
            $this->model->{$this->attribute},
        );
    }
}