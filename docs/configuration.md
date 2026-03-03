# Configuration reference

## Overview

This guide describes form model conventions and runtime behavior for metadata and validation errors.

## Form model declaration

Create form models by extending `AbstractFormModel` and declaring typed public properties that represent fields.

```php
<?php

declare(strict_types=1);

namespace App\FormModel;

use UIAwesome\FormModel\AbstractFormModel;

final class SignInForm extends AbstractFormModel
{
    public string $email = '';
    public string $password = '';

    public function getHints(): array
    {
        return [
            'email' => 'Use your account email address.',
            'password' => 'Use at least 8 characters.',
        ];
    }

    public function getLabels(): array
    {
        return [
            'email' => 'Email address',
            'password' => 'Password',
        ];
    }

    public function getPlaceholders(): array
    {
        return [
            'email' => 'name@example.com',
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
            'email' => [
                'class()' => ['w-full rounded-md border border-slate-300 px-3 py-2'],
            ],
        ];
    }
}
```

## Metadata access behavior

- `getHint()`, `getLabel()`, and `getPlaceholder()` resolve values from their map methods.
- Missing labels fall back to generated title-case labels.
- `getRule()` returns field rules or `null` when no rules exist.
- `getFieldConfig()` returns field configuration for a single field.

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

- [Usage examples](examples.md)
- [Testing guide](testing.md)
