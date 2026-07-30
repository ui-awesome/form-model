# Upgrade Guide

## 0.2.0

### Renamed API

Update implementations and call sites to the shorter field-oriented API:

| Before                                                           | After               |
| ---------------------------------------------------------------- | ------------------- |
| `AbstractFormModel`                                              | `BaseFormModel`     |
| `getWidgetConfigByProperties()` / `getFieldConfigByProperties()` | `getFieldConfigs()` |
| `getWidgetConfigByProperty()` / `getFieldConfigByProperty()`     | `getFieldConfig()`  |
| `addPropertyError()`                                             | `addError()`        |
| `getHintByProperty()`                                            | `getHint()`         |
| `getLabelByProperty()`                                           | `getLabel()`        |
| `getPlaceholderByProperty()`                                     | `getPlaceholder()`  |
| `getRulesByProperty()`                                           | `getRule()`         |
| `hasPropertyError()`                                             | `hasError()`        |
| `hasPropertyValidate()`                                          | `isValidated()`     |

First-error retrieval now has explicit methods:

- Replace `getErrors(first: true)` with `getFirstErrors()`.
- Replace `getPropertyError($field, true)` with `getFirstError($field)`.
- Replace `getPropertyError($field)` with `getError($field)`.

### Removed API

The following methods were removed:

- `applyToHtmlRulesByProperty()`
- `getWidgetConfig()`
- `getWidgetConfigByClass()`

Move HTML rule application to the field rendering layer and class-level widget defaults to field entries returned by
`getFieldConfigs()`.

### Field metadata

`FieldMetadata` was removed. `BaseFormModel` now resolves dot notation directly from `getFieldConfig()`, `getHint()`,
`getLabel()`, `getPlaceholder()`, and `getRule()`.

Before:

```php
final class ProfileForm extends AbstractFormModel
{
    public function getFieldConfigByProperties(): array
    {
        return [];
    }
}
```

After:

```php
final class ProfileForm extends BaseFormModel
{
    public function getFieldConfigs(): array
    {
        return [];
    }
}
```
