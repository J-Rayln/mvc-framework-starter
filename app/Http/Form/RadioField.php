<?php

namespace JonathanRayln\UdemyClone\Http\Form;

use JonathanRayln\UdemyClone\Models\BaseModel;

class RadioField extends BaseField
{
    /** @var array key => value pairs to be used for each radio input */
    public array $values = [];

    public function __construct(BaseModel $model, string $attribute, array $values)
    {
        $this->values = $values;
        parent::__construct($model, $attribute);
    }

    /**
     * Renders a set of radio input form fields.
     *
     * @return string
     */
    public function renderInput(): string
    {
        $inputs = '';
        foreach ($this->values as $value => $label) {
            $inputs .= sprintf(
                '<div class="form-check">
                            <input class="form-check-input%s" type="radio" name="%s" id="%s" value="%s"%s%s>
                            <label class="form-check-label" for="%s">%s</label>
                        </div>',
                $this->model->hasError($this->attribute) ? ' is-invalid' : '',
                $this->attribute,
                $this->attribute,
                $value,
                $this->oldChecked($value) ? ' checked' : '',
                $this->disabled ? ' disabled="disabled"' : '',
                $this->attribute,
                $label
            );
        }

        return $inputs;
    }
}