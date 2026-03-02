# Configuration reference

## Overview

This guide describes form model conventions and runtime behavior for metadata and validation errors.

## Form model declaration

Create form models by extending `AbstractFormModel` and declaring typed properties.

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

    public function getFieldConfigByProperties(): array
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

- `getHintByProperty()`, `getLabelByProperty()`, and `getPlaceholderByProperty()` resolve values from their map methods.
- Missing labels fall back to generated title-case labels.
- `getRulesByProperty()` returns property rules or `null` when no rules exist.
- `getFieldConfigByProperty()` returns field configuration for a single property.

```php
$label = $form->getLabelByProperty('email');
$placeholder = $form->getPlaceholderByProperty('email');
$rules = $form->getRulesByProperty('email');
$fieldConfig = $form->getFieldConfigByProperty('email');
```

## Nested metadata

- Use dot notation to access metadata from nested form models.
- Nested paths work for hints, labels, placeholders, rules, and field configuration.

```php
$hint = $form->getHintByProperty('profile.address.city');
$label = $form->getLabelByProperty('profile.address.city');
$rules = $form->getRulesByProperty('profile.address.city');
```

## Error lifecycle

- `addPropertyError()` appends a message to one property.
- `setErrors()` replaces all property error collections.
- `clearError()` clears all errors or one property.
- `getErrors(first: true)` returns the first error per property.
- `getErrorSummary()` flattens all messages for rendering.
- `hasPropertyError()` and `hasPropertyValidate()` report current error state.

```php
$form->addPropertyError('email', 'Email is required.');

$errors = $form->getErrors();
// ['email' => ['Email is required.']]

$firstErrors = $form->getErrors(first: true);
// ['email' => 'Email is required.']

$summary = $form->getErrorSummary();
// ['Email is required.']
```

## Next steps

- [Usage examples](examples.md)
- [Testing guide](testing.md)
