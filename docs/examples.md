# Usage examples

This document provides practical examples for metadata resolution, nested fields, and error handling.

## Basic form model

```php
<?php

declare(strict_types=1);

namespace App\FormModel;

use UIAwesome\FormModel\BaseFormModel;

final class ContactForm extends BaseFormModel
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

echo $form->getLabel('name');
// "Full name"

echo $form->getHint('email');
// "Use a valid email address."
```

## Generated fallback labels

```php
<?php

declare(strict_types=1);

namespace App\FormModel;

use UIAwesome\FormModel\BaseFormModel;

final class PostForm extends BaseFormModel
{
    public string $seoTitle = '';
}

$form = new PostForm();

echo $form->getLabel('seoTitle');
// "Seo Title"
```

## Error collection per field

```php
<?php

$form = new ContactForm();

$form->addError('email', 'Email is required.');
$form->addError('email', 'Email format is invalid.');

print_r($form->getError('email'));
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

$form->setErrors(
    [
        'email' => ['Email is required.', 'Email format is invalid.'],
        'password' => ['Password is required.'],
    ],
);

print_r($form->getFirstErrors());
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

use UIAwesome\FormModel\BaseFormModel;

final class AddressForm extends BaseFormModel
{
    public string $city = '';

    public function getLabels(): array
    {
        return ['city' => 'City'];
    }
}

final class ProfileForm extends BaseFormModel
{
    public AddressForm $address;

    public function __construct()
    {
        $this->address = new AddressForm();
    }
}

$form = new ProfileForm();

echo $form->getLabel('address.city');
// "City"
```

## Field configuration metadata

```php
<?php

declare(strict_types=1);

namespace App\FormModel;

use UIAwesome\FormModel\BaseFormModel;

final class LoginForm extends BaseFormModel
{
    public string $email = '';

    public function getFieldConfigs(): array
    {
        return [
            'email' => [
                'class()' => ['w-full rounded-md border border-slate-300 px-3 py-2'],
            ],
        ];
    }
}

$form = new LoginForm();

print_r($form->getFieldConfig('email'));
```

## Next steps

- [Installation guide](installation.md)
- [Configuration reference](configuration.md)
- [Testing guide](testing.md)
