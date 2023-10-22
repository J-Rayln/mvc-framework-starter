<?php

namespace JonathanRayln\UdemyClone\Http\Form;

use JonathanRayln\UdemyClone\Database\DbModel;
use JonathanRayln\UdemyClone\Models\BaseModel;

class Form
{
    /**
     * Instantiates a new form and prints the opening `<form>` tag.
     *
     * @param string      $customClasses Optional.  Custom CSS classes to add
     *                                   to the opening <form> element.
     * @param string|null $action        URL to send the form to.
     * @param string      $method        Default 'post'.  Method to use.
     *                                   Accepts 'get','post', 'put', 'patch', 'delete'.
     * @param bool        $multipart     Default 'false'.  Set 'true' for a
     *                                   'enctype=multipart/form-data'.
     * @return Form
     */
    public static function start(string $customClasses = '', ?string $action = '', string $method = 'post', bool $multipart = false): Form
    {
        $form = new Form();

        if (!empty($action)) {
            $action = ' action="' . $action . '"';
        }
        if (strtolower($method) === 'post' && $multipart === true) {
            $multipart = ' enctype="multipart/form-data"';
        }

        if ($method != 'post' && $method != 'get') {
            $hiddenMethod = $method;
            $method = 'post';
            $hiddenMethodInput = PHP_EOL . '<input type="hidden" name="_method" value="' . $hiddenMethod . '">';
        }

        echo sprintf('<form%s method="%s"%s%s>%s' . PHP_EOL,
            $action,
            $method,
            $multipart ?? '',
            $customClasses ? ' class="' . $customClasses . '"' : '',
            $hiddenMethodInput ?? ''
        );
        echo PHP_EOL;

        return $form;
    }

    /**
     * Prints the closing `</form>` tag.
     *
     * @return void
     */
    public static function end(): void
    {
        echo '</form>' . PHP_EOL;
    }

    /**
     * Creates a label for a form input.
     *
     * @param BaseModel $model                                      Model passed from the controller.
     * @param string    $attribute                                  Table column name. Must match associated
     *                                                              input method call.
     * @param bool      $hidden                                     Default 'false'.  Set 'true' to make
     *                                                              the label visually hidden (for screen
     *                                                              readers).
     * @return Label
     */
    public function label(BaseModel $model, string $attribute, bool $hidden = false): Label
    {
        return new Label($model, $attribute, $hidden);
    }

    /**
     * Instantiates and renders a new input field.  Method can be chained with
     * helper methods to modify `type=`.  Default type is 'text'.
     *
     * Available methods are:<ul>
     *     <li>`typeEmail()`</li>
     *     <li>`typeNumber()`</li>
     *     <li>`typeHidden()`</li>
     *     <li>`typePassword()`</li></ul>
     *
     * @param BaseModel   $model       Model passed from the controller.
     * @param string      $attribute   Table column name.
     * @param string|null $placeholder Placeholder text to be displayed in the input field.
     * @return InputField
     */
    public function input(BaseModel $model, string $attribute, ?string $placeholder = null): InputField
    {
        return new InputField($model, $attribute, $placeholder);
    }

    /**
     * Creates a new textarea field for the model and attribute specified.
     *
     * @param BaseModel $model     Model passed from the controller.
     * @param string    $attribute Table column name.
     * @param int|null  $rows      Value for `rows=` attribute. Default is 3.
     * @return TextareaField
     */
    public function textarea(BaseModel $model, string $attribute, ?int $rows = 3): TextareaField
    {
        return new TextareaField($model, $attribute, $rows);
    }

    /**
     * Creates a new radio field group for the model and attribute specified.
     *
     * $values array must be `key => value` pairs where<br />
     *      <li>`key`   = the human-readable value that is displayed to the user</li>
     *      <li>'value' = the actual value assigned to the `value=` attribute of the `<input>` tag.</li>
     *
     * @param BaseModel $model                                      Model passed from the controller.
     * @param string    $attribute                                  Field name.  MUST be identical to the database field name.
     * @param array     $values                                     Array of values to be used in the form of
     *                                                              ['display_label' => 'attribute_value']
     * @return RadioField
     */
    public function radio(BaseModel $model, string $attribute, array $values): RadioField
    {
        return new RadioField($model, $attribute, $values);
    }

    /**
     * Creates a new checkbox field group for the model and attribute specified.
     *
     * @param BaseModel   $model     Model passed from the controller.
     * @param string      $attribute Field name.  MUST be identical to the database field name.
     * @param string|int  $value     The value of the checkbox field when checked.
     * @param string|null $labelText The text to display to the right of the checkbox field.
     * @return CheckboxField
     */
    public function checkbox(BaseModel $model, string $attribute, string|int $value, ?string $labelText): CheckboxField
    {
        return new CheckboxField($model, $attribute, $value, $labelText);
    }

    /**
     * Creates a new select field group for the model and attribute specified.
     *
     * $values array must be `key => value` pairs where<br />
     *       <li>`key`   = the human-readable value that is displayed to the user</li>
     *       <li>'value' = the actual value assigned to the `value=` attribute of the `<input>` tag.</li>
     *
     * @param BaseModel $model                                      Model passed from the controller.
     * @param string    $attribute                                  Field name.  MUST be identical to the database field name.
     * @param array     $values                                     Array of values to be used in the form of
     *                                                              ['display_label' => 'attribute_value']
     * @return SelectField
     */
    public function select(BaseModel $model, string $attribute, array $values): SelectField
    {
        return new SelectField($model, $attribute, $values);
    }

    /**
     * Returns a list of options for use in select and radio fields.
     *
     * @param string $tableName     Table to query.
     * @param string $displayColumn Column values that will be used to display in the select field.
     * @param string $valueColumn   Column value that will be used in the `value=` attribute
     * @return array
     */
    public static function getOptionsList(string $tableName, string $displayColumn, string $valueColumn): array
    {
        $results = DbModel::findAll($tableName);
        $resultsList = [];
        foreach ($results as $result) {
            $resultsList[$result[$valueColumn]] = $result[$displayColumn];
        }

        return $resultsList;
    }
}