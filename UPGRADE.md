# Upgrade Guide

## 0.2.0

### Breaking changes

#### Presentation-coupled API removed

- `applyToHtmlRulesByProperty(InputInterface $input, string $property)` was removed from:
  - `UIAwesome\FormModel\FormModelInterface`
  - `UIAwesome\FormModel\AbstractFormModel`

#### Dependency removed

- `ui-awesome/html-interop` is no longer required.
- Any direct usage of `InputInterface` with form-model must be removed from consumer code.

#### Field-level presentation configuration API renamed

- `getWidgetConfigByProperties()` -> `getFieldConfigByProperties()` -> `getFieldConfigs()`
- `getWidgetConfigByProperty(string $property)` -> `getFieldConfigByProperty(string $property)` -> `getFieldConfig(string $field)`

#### Form-model metadata and error API renamed for consistency

- `addPropertyError(string $property, string $error)` -> `addError(string $field, string $error)`
- `getHintByProperty(string $property)` -> `getHint(string $field)`
- `getLabelByProperty(string $property)` -> `getLabel(string $field)`
- `getPlaceholderByProperty(string $property)` -> `getPlaceholder(string $field)`
- `getRulesByProperty(string $property)` -> `getRule(string $field)`
- `hasPropertyError(string|null $property = null)` -> `hasError(string|null $field = null)`
- `hasPropertyValidate(string $property)` -> `isValidated(string $field)`
- `getErrors(bool $first = false)` split into `getErrors()` and `getFirstErrors()`
- `getPropertyError(string $property, bool $first = false)` split into `getError(string $field)` and `getFirstError(string $field)`

#### Widget-class-based configuration API removed

- `getWidgetConfig()` removed
- `getWidgetConfigByClass(string $class)` removed

#### Base class renamed

- `UIAwesome\FormModel\AbstractFormModel` -> `UIAwesome\FormModel\BaseFormModel`

#### Dot-notation metadata resolution moved into `BaseFormModel` getters

- `FieldMetadata` class removed
- Dot-notation support added to `getFieldConfig()`, `getHint()`, `getLabel()`, `getPlaceholder()`, and `getRule()` methods in `BaseFormModel`

### Migration steps

#### Replace old method names in custom form models

```php
// Before
public function getFieldConfigByProperties(): array

// After
public function getFieldConfigs(): array
```

#### Replace old consumer calls

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

#### Update first-error retrieval calls

- Replace `getErrors(first: true)` with `getFirstErrors()`.
- Replace `getPropertyError('field', true)` with `getFirstError('field')`.

#### Remove any usage of removed APIs

- Delete calls to `getWidgetConfig()` and `getWidgetConfigByClass()`.
- Move any class-level defaults to field-level config in `getFieldConfigs()`.

#### Remove presentation rule application from the form model

- Remove calls to `applyToHtmlRulesByProperty(...)`.
- Move HTML/tag rule application to the field/tag rendering layer.

#### Update base-class inheritance in custom form models

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
