# Configuration reference

## Overview

This guide describes form model conventions and runtime behavior for metadata and validation errors.

## Form model declaration

Create form models by extending `BaseFormModel` and declaring typed public properties that represent fields.

```php
<?php

declare(strict_types=1);

namespace App\FormModel;

use UIAwesome\FormModel\Attribute\{FieldConfig, Hint, Label, Placeholder};
use UIAwesome\FormModel\BaseFormModel;

final class SignInForm extends BaseFormModel
{
    #[Hint('Use your account email address.')]
    #[Label('Email address')]
    #[Placeholder('name@example.com')]
    #[FieldConfig(['class' => ['w-full rounded-md border border-slate-300 px-3 py-2']])]
    public string $email = '';

    public string $password = '';

    public function getHints(): array
    {
        return [
            'password' => 'Use at least 8 characters.',
        ];
    }

    public function getLabels(): array
    {
        return [
            'password' => 'Password',
        ];
    }

    public function getPlaceholders(): array
    {
        return [
            'password' => 'Enter your password',
        ];
    }

    public function getRules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function getFieldConfigs(): array
    {
        return [
            'password' => [
                'class' => ['w-full rounded-md border border-slate-300 px-3 py-2'],
            ],
        ];
    }
}
```

## Property metadata attributes

You can declare metadata directly on properties with attributes:

- `#[Hint('...')]`
- `#[Label('...')]`
- `#[Placeholder('...')]`
- `#[FieldConfig([...])]`

When a property has both attribute metadata and map metadata, the attribute value is used first.

## Metadata access behavior

- `getHint()`, `getLabel()`, and `getPlaceholder()` resolve values from attributes first, then map methods.
- Missing labels fall back to generated title-case labels.
- `getRule()` returns field rules or `null` when no rules exist.
- `getFieldConfig()` resolves field configuration from attributes first, then map methods.

```php
$label = $form->getLabel('email');
$placeholder = $form->getPlaceholder('email');
$rules = $form->getRule('email');
$fieldConfig = $form->getFieldConfig('email');
```

## Nested metadata

- Use dot notation to access metadata from nested form models.
- Nested paths work for hints, labels, placeholders, rules, and field configuration.

```php
$hint = $form->getHint('profile.address.city');
$label = $form->getLabel('profile.address.city');
$rules = $form->getRule('profile.address.city');
```

## Error lifecycle

- `addError()` appends a message to one field.
- `setErrors()` replaces all field error collections.
- `clearError()` clears all errors or one field.
- `getFirstErrors()` returns the first error per field.
- `getErrorSummary()` flattens all messages for rendering.
- `hasError()` and `isValidated()` report current error state.

```php
$form->addError('email', 'Email is required.');

$errors = $form->getErrors();
// ['email' => ['Email is required.']]

$firstErrors = $form->getFirstErrors();
// ['email' => 'Email is required.']

$summary = $form->getErrorSummary();
// ['Email is required.']
```

## Next steps

- 💡 [Usage examples](examples.md)
- 🧪 [Testing guide](testing.md)
