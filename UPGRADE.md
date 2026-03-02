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

3. Renamed property-level presentation configuration API (widget -> field):
    - `getWidgetConfigByProperties()` -> `getFieldConfigByProperties()`
    - `getWidgetConfigByProperty(string $property)` -> `getFieldConfigByProperty(string $property)`

4. Removed widget-class-based configuration API:
    - `getWidgetConfig()` removed
    - `getWidgetConfigByClass(string $class)` removed

### Migration steps

1. Replace old method names in custom form models:

```php
// Before
public function getWidgetConfigByProperties(): array

// After
public function getFieldConfigByProperties(): array
```

2. Replace old consumer calls:

```php
// Before
$model->getWidgetConfigByProperty('name');

// After
$model->getFieldConfigByProperty('name');
```

3. Remove any usage of removed APIs:
    - Delete calls to `getWidgetConfig()` and `getWidgetConfigByClass()`.
    - Move any class-level defaults to property-level config in `getFieldConfigByProperties()`.

4. Remove calls to `applyToHtmlRulesByProperty(...)` and move HTML/tag rule application to the field/tag rendering layer.
