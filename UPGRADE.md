# Upgrade Guide

## 0.2.0

### Breaking changes

1. Removed presentation-coupled API:
    - `applyToHtmlRulesByProperty(InputInterface $input, string $property)` was removed from:
        - `UIAwesome\FormModel\FormModelInterface`
        - `UIAwesome\FormModel\AbstractFormModel`

2. Removed dependency:
    - `ui-awesome/html-interop` is no longer required.
    - Any direct usage of `InputInterface` with form-model must be removed from consumer code.

3. Renamed field-level presentation configuration API:
    - `getWidgetConfigByProperties()` -> `getFieldConfigByProperties()` -> `getFieldConfigs()`
    - `getWidgetConfigByProperty(string $property)` -> `getFieldConfigByProperty(string $property)` -> `getFieldConfig(string $field)`

4. Renamed form-model metadata and error API for consistency:
    - `addPropertyError(string $property, string $error)` -> `addError(string $field, string $error)`
    - `getHintByProperty(string $property)` -> `getHint(string $field)`
    - `getLabelByProperty(string $property)` -> `getLabel(string $field)`
    - `getPlaceholderByProperty(string $property)` -> `getPlaceholder(string $field)`
    - `getRulesByProperty(string $property)` -> `getRule(string $field)`
    - `hasPropertyError(string|null $property = null)` -> `hasError(string|null $field = null)`
    - `hasPropertyValidate(string $property)` -> `isValidated(string $field)`
    - `getErrors(bool $first = false)` split into `getErrors()` and `getFirstErrors()`
    - `getPropertyError(string $property, bool $first = false)` split into `getError(string $field)` and `getFirstError(string $field)`

5. Removed widget-class-based configuration API:
    - `getWidgetConfig()` removed
    - `getWidgetConfigByClass(string $class)` removed

6. Renamed base class:
    - `UIAwesome\FormModel\AbstractFormModel` -> `UIAwesome\FormModel\BaseFormModel`

### Migration steps

1. Replace old method names in custom form models:

```php
// Before
public function getFieldConfigByProperties(): array

// After
public function getFieldConfigs(): array
```

2. Replace old consumer calls:

```php
// Before
$model->getFieldConfigByProperty('name');
$model->getHintByProperty('name');
$model->getPropertyError('name', true);

// After
$model->getFieldConfig('name');
$model->getHint('name');
$model->getFirstError('name');
```

3. Update first-error retrieval calls:
    - Replace `getErrors(first: true)` with `getFirstErrors()`.
    - Replace `getPropertyError('field', true)` with `getFirstError('field')`.

4. Remove any usage of removed APIs:
    - Delete calls to `getWidgetConfig()` and `getWidgetConfigByClass()`.
    - Move any class-level defaults to field-level config in `getFieldConfigs()`.

5. Remove calls to `applyToHtmlRulesByProperty(...)` and move HTML/tag rule application to the field/tag rendering layer.

6. Update base-class inheritance in custom form models:

```php
// Before
use UIAwesome\FormModel\AbstractFormModel;

final class MyForm extends AbstractFormModel
{
}

// After
use UIAwesome\FormModel\BaseFormModel;

final class MyForm extends BaseFormModel
{
}
```
