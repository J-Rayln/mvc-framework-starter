<?php

namespace JonathanRayln\UdemyClone\Models;

use JonathanRayln\UdemyClone\Application;

abstract class BaseModel
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_UNIQUE = 'unique';
    public const RULE_MATCH = 'match';
    public const RULE_STRONG_PASSWORD = 'strong_password';

    public array $errors = [];

    /**
     * Checks to see if the $key exists in the model for each $key/$value pair
     * and assigns the value to that key.
     *
     * @param array $data
     * @return void
     */
    public function loadData(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Associative array of form attribute names and validation errors to
     * test against.
     *
     * This method must be present in all subclasses which extend this class
     * that have forms.
     *
     * @return array
     */
    abstract public function rules(): array;

    /**
     * Associative array of field labels with corresponding human-readable
     * labels that will be displayed.
     *
     * Override in child class.
     *
     * Example usage:
     *    return [
     *      'firstname' => 'First Name',
     *      'lastname' => 'Last Name',
     *    ];
     *
     * @return array
     */
    public function labels(): array
    {
        return [];
    }

    /**
     * Returns the human-readable label for the given attribute.
     *
     * @param string $attribute
     * @return string
     */
    public function getLabel(string $attribute): string
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    /**
     * Validates form input and adds errors to the errors array.
     *
     * @return bool
     */
    public function validate(): bool
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrorForRule($attribute, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                }
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                }
                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $rule['match'] = $this->getLabel($rule['match']);
                    $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                }
                if ($ruleName === self::RULE_UNIQUE) {
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                    $statement->bindValue(":attr", $value);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if ($record) {
                        $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);
                    }
                }
                if ($ruleName === self::RULE_STRONG_PASSWORD) {
                    $uppercase = preg_match('@[A-Z]@', $value);
                    $number = preg_match('@[0-9]@', $value);
                    $specialChars = preg_match('@\W@', $value);
                    // $specialChars = preg_match('@[^\w]@', $value);
                    if (!$uppercase || !$number || !$specialChars || strlen($value) < $rule['min'] || strlen($value) > $rule['max']) {
                        $this->addErrorForRule($attribute, self::RULE_STRONG_PASSWORD, $rule);
                    }
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Adds errors messages to the errors array based on rule validation and
     * error text defined in the errorMessages() method.
     *
     * @param string $attribute Attribute key to assign the error to.
     * @param string $rule      Rule to validate against.
     * @param array  $params    Additional arguments to pass to the rule message.
     * @return void
     */
    private function addErrorForRule(string $attribute, string $rule, array $params = []): void
    {
        $message = $this->errorMessages()[$rule] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    /**
     * Adds an error for the given $attribute to the $errors[] array.
     *
     * @param string $attribute
     * @param string $message
     * @return void
     */
    public function addError(string $attribute, string $message): void
    {
        $this->errors[$attribute][] = $message;
    }

    /**
     * Associative array of error messages for validation rule failures.
     *
     * @return array
     */
    public function errorMessages(): array
    {
        return [
            self::RULE_REQUIRED        => 'This field is required',
            self::RULE_EMAIL           => 'This field must be a valid email address',
            self::RULE_MIN             => 'Min length of this field must be {min}',
            self::RULE_MAX             => 'Max length of this field must be {max}',
            self::RULE_MATCH           => 'This field must be the same as {match}',
            self::RULE_UNIQUE          => 'Record with this {field} already exists',
            self::RULE_STRONG_PASSWORD => 'Passwords must be between {min} and {max} characters, and contain at least 1 uppercase letter, 1 number, and 1 special character'
        ];
    }

    /**
     * Returns whether there are errors for a given attribute key.
     *
     * @param string $attribute Attribute key.
     * @return array|bool
     */
    public function hasError(string $attribute): array|bool
    {
        return $this->errors[$attribute] ?? false;
    }

    /**
     * Returns the first error in the errors array for a given attribute key.
     *
     * @param string $attribute Attribute key to return errors for.
     * @return mixed
     */
    public function getFirstError(string $attribute): mixed
    {
        return $this->errors[$attribute][0] ?? false;
    }
}