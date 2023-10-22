<?php

namespace JonathanRayln\UdemyClone\Http\Form;

use JonathanRayln\UdemyClone\Models\BaseModel;

class InputField extends BaseField
{
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';
    public const TYPE_EMAIL = 'email';
    public const TYPE_HIDDEN = 'hidden';

    /** @var string Value of `type=` attribute. */
    public string $type;

    public ?string $placeholder;

    /**
     * @param BaseModel   $model
     * @param string      $attribute
     * @param string|null $placeholder
     */
    public function __construct(BaseModel $model, string $attribute, ?string $placeholder)
    {
        $this->type = self::TYPE_TEXT;
        $this->placeholder = $placeholder;
        parent::__construct($model, $attribute);
    }

    /**
     * Chainable method that changes the `type=` attribute to 'password'.
     *
     * @return $this
     */
    public function typePassword(): static
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    /**
     * Chainable method that changes the `type=` attribute to 'email'.
     *
     * @return $this
     */
    public function typeEmail(): static
    {
        $this->type = self::TYPE_EMAIL;
        return $this;
    }

    /**
     * Chainable method that changes the `type=` attribute to 'number'.
     *
     * @return $this
     */
    public function typeNumber(): static
    {
        $this->type = self::TYPE_NUMBER;
        return $this;
    }

    /**
     * Chainable method that changes the `type=` attribute to 'hidden'.
     *
     * @return $this
     */
    public function typeHidden(): static
    {
        $this->type = self::TYPE_HIDDEN;
        return $this;
    }

    public function renderInput(): string
    {
        return sprintf(
            '<input type="%s" name="%s" id="%s" class="form-control%s" value="%s"%s%s>' . PHP_EOL,
            $this->type,
            $this->attribute,
            $this->attribute,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->model->{$this->attribute},
            $this->placeholder ? ' placeholder="' . $this->placeholder . '"' : '',
            $this->disabled ? ' disabled="disabled"' : '',
        );
    }
}