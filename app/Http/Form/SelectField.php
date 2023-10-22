<?php

namespace JonathanRayln\UdemyClone\Http\Form;

use JonathanRayln\UdemyClone\Models\BaseModel;

class SelectField extends BaseField
{
    /** @var array key => value pairs to be used for each select <option> */
    public array $values = [];

    public function __construct(BaseModel $model, string $attribute, array $values)
    {
        $this->values = $values;
        parent::__construct($model, $attribute);
    }

    public function renderInput(): string
    {
        $inputs = '';
        foreach ($this->values as $value => $label) {
            $inputs .= '<option value="' . $value . '"' . ($this->oldChecked($value) ? ' selected' : '') . '>' . $label . '</option>';
        }

        return sprintf(
            '<select class="form-control%s" id=%s" name="%s"%s>
                    <option value=""> -- choose one -- </option>
                    %s
                    </select>',
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->attribute,
            $this->attribute,
            $this->disabled ? ' disabled="disabled"' : '',
            $inputs
        );
    }
}