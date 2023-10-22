<?php

namespace JonathanRayln\UdemyClone\Http\Form;

use JonathanRayln\UdemyClone\Models\BaseModel;

class CheckboxField extends BaseField
{
    public string|int $value;
    public ?string $labelText;

    public function __construct(BaseModel $model, string $attribute, string|int $value, ?string $labelText)
    {
        $this->value = $value;
        $this->labelText = $labelText;
        parent::__construct($model, $attribute);
    }

    /**
     * Renders a checkbox input form field.
     *
     * @return string
     */
    public function renderInput(): string
    {
        return sprintf(
            '<label class="form-check">
                            <input type="checkbox" class="form-check-input%s" name="%s" value="%s"%s%s>
                            <span class="form-check-label">%s</span>
                        </label>' . PHP_EOL,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->attribute,
            $this->value,
            $this->oldChecked($this->model->{$this->attribute}) ? ' checked' : '',
            $this->disabled ? ' disabled="disabled"' : '',
            $this->labelText ?? '',
        );

    }
}