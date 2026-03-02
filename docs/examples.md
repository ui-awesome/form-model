# Usage examples

This document provides practical examples for metadata resolution, nested properties, and error handling.

## Basic form model

```php
<?php

declare(strict_types=1);

namespace App\FormModel;

use UIAwesome\FormModel\AbstractFormModel;

final class ContactForm extends AbstractFormModel
{
    public string $name = '';
    public string $email = '';

    public function getHints(): array
    {
        return [
            'name' => 'Enter your full name.',
            'email' => 'Use a valid email address.',
        ];
    }

    public function getLabels(): array
    {
        return [
            'name' => 'Full name',
            'email' => 'Email address',
        ];
    }

    public function getPlaceholders(): array
    {
        return [
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
        ];
    }
}

$form = new ContactForm();

echo $form->getLabelByProperty('name');
// "Full name"

echo $form->getHintByProperty('email');
// "Use a valid email address."
```

## Generated fallback labels

```php
<?php

declare(strict_types=1);

namespace App\FormModel;

use UIAwesome\FormModel\AbstractFormModel;

final class PostForm extends AbstractFormModel
{
    public string $seoTitle = '';
}

$form = new PostForm();

echo $form->getLabelByProperty('seoTitle');
// "Seo Title"
```

## Error collection per property

```php
<?php

$form->addPropertyError('email', 'Email is required.');
$form->addPropertyError('email', 'Email format is invalid.');

print_r($form->getPropertyError('email'));
/*
[
    'Email is required.',
    'Email format is invalid.',
]
*/
```

## First-error mode and summary output

```php
<?php

$form->setErrors([
    'email' => ['Email is required.', 'Email format is invalid.'],
    'password' => ['Password is required.'],
]);

print_r($form->getErrors(first: true));
/*
[
    'email' => 'Email is required.',
    'password' => 'Password is required.',
]
*/

print_r($form->getErrorSummary());
/*
[
    'Email is required.',
    'Email format is invalid.',
    'Password is required.',
]
*/
```

## Nested metadata access

```php
<?php

declare(strict_types=1);

namespace App\FormModel;

use UIAwesome\FormModel\AbstractFormModel;

final class AddressForm extends AbstractFormModel
{
    public string $city = '';

    public function getLabels(): array
    {
        return ['city' => 'City'];
    }
}

final class ProfileForm extends AbstractFormModel
{
    public AddressForm $address;

    public function __construct()
    {
        $this->address = new AddressForm();
    }
}

$form = new ProfileForm();

echo $form->getLabelByProperty('address.city');
// "City"
```

## Field configuration metadata

```php
<?php

declare(strict_types=1);

namespace App\FormModel;

use UIAwesome\FormModel\AbstractFormModel;

final class LoginForm extends AbstractFormModel
{
    public string $email = '';

    public function getFieldConfigByProperties(): array
    {
        return [
            'email' => [
                'class()' => ['w-full rounded-md border border-slate-300 px-3 py-2'],
            ],
        ];
    }
}

$form = new LoginForm();

print_r($form->getFieldConfigByProperty('email'));
```

## Next steps

- [Installation guide](installation.md)
- [Configuration reference](configuration.md)
- [Testing guide](testing.md)
