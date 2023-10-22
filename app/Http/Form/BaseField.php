<?php

namespace JonathanRayln\UdemyClone\Http\Form;

use JonathanRayln\UdemyClone\Models\BaseModel;

abstract class BaseField
{
    protected BaseModel $model;
    protected string $attribute;
    protected bool $disabled = false;

    /**
     * @param BaseModel $model     Model passed from the controller.
     * @param string    $attribute Table column name.
     */
    public function __construct(BaseModel $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    abstract public function renderInput(): string;

    /**
     * Outputs the field along with the appropriate invalid feedback classes
     * and div.
     *
     * @return string
     */
    public function __toString(): string
    {
        $invalidFeedback = $this->model->hasError($this->attribute) ?
            sprintf(
                '<div class="invalid-feedback d-block">%s</div>' . PHP_EOL,
                $this->model->getFirstError($this->attribute)
            ) : '';

        return sprintf(
            '%s%s',
            $this->renderInput(),
            $invalidFeedback
        );
    }

    /**
     * Returns 'checked' on radio buttons and checkboxes for previously selected
     * options when refreshing the form to show error messages or other feedback.
     *
     * @param int|string|bool $value The value of the checkbox or radio button.
     * @return bool
     */
    public function oldChecked(int|string|bool|null $value): bool
    {
        if (isset($this->model->{$this->attribute}) && $this->model->{$this->attribute} == $value) {
            return true;
        }

        return false;
    }

    public function disabled(): static
    {
        $this->disabled = true;
        return $this;
    }
}