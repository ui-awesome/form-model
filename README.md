<!-- markdownlint-disable MD041 -->
<p align="center">
    <a href="https://github.com/ui-awesome/form-model" target="_blank">
        <img src="https://avatars.githubusercontent.com/u/103309199?s%25253D400%252526u%25253Dca3561c692f53ed7eb290d3bb226a2828741606f%252526v%25253D4" height="100px" alt="UIAwesome">
    </a>
    <h1 align="center">UIAwesome Form Model for PHP</h1>
    <br>
</p>
<!-- markdownlint-enable MD041 -->

<p align="center">
    <a href="https://github.com/ui-awesome/form-model/actions/workflows/build.yml" target="_blank">
        <img src="https://img.shields.io/github/actions/workflow/status/ui-awesome/form-model/build.yml?style=for-the-badge&label=PHPUnit&logo=github" alt="PHPUnit">
    </a>
    <a href="https://dashboard.stryker-mutator.io/reports/github.com/ui-awesome/form-model/main" target="_blank">
        <img src="https://img.shields.io/endpoint?style=for-the-badge&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fui-awesome%2Fform-model%2Fmain" alt="Mutation Testing">
    </a>
    <a href="https://github.com/ui-awesome/form-model/actions/workflows/static.yml" target="_blank">
        <img src="https://img.shields.io/github/actions/workflow/status/ui-awesome/form-model/static.yml?style=for-the-badge&label=PHPStan&logo=github" alt="PHPStan">
    </a>
</p>

<p align="center">
    <strong>Form metadata and validation errors for model-driven PHP forms</strong><br>
    <em>Hints, labels, placeholders, field configuration, nested property metadata, and property-scoped error handling</em>
</p>

## Features

<picture>
    <source media="(min-width: 768px)" srcset="./docs/svgs/features.svg">
    <img src="./docs/svgs/features-mobile.svg" alt="Feature Overview" style="width: 100%;">
</picture>

## Installation

```bash
composer require ui-awesome/form-model:^0.2
```

## Quick start

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

$form = new SignInForm();

$form->addPropertyError('email', 'Email is required.');

$errors = $form->getErrors();
/*
[
    'email' => ['Email is required.'],
]
*/

$summary = $form->getErrorSummary();
// ['Email is required.']

$label = $form->getLabelByProperty('email');
// 'Email address'
```

## Nested property metadata

You can request metadata using dot notation when a property contains another `AbstractFormModel`.

```php
$hint = $form->getHintByProperty('profile.address.city');
$label = $form->getLabelByProperty('profile.address.city');
$placeholder = $form->getPlaceholderByProperty('profile.address.city');
$rules = $form->getRulesByProperty('profile.address.city');
```

## Error collection and first-error mode

Use first-error mode when you need one message per property.

```php
$form->setErrors([
    'email' => ['Email is required.', 'Email is invalid.'],
    'password' => ['Password is required.'],
]);

$firstErrors = $form->getErrors(first: true);
/*
[
    'email' => 'Email is required.',
    'password' => 'Password is required.',
]
*/
```

## Documentation

For setup details and advanced usage.

- [Installation guide](docs/installation.md)
- [Configuration reference](docs/configuration.md)
- [Usage examples](docs/examples.md)
- [Testing guide](docs/testing.md)
- [Development guide](docs/development.md)
- [Upgrade guide](UPGRADE.md)

## Package information

[![PHP](https://img.shields.io/badge/%3E%3D8.1-777BB4.svg?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/releases/8.1/en.php)
[![Latest Stable Version](https://img.shields.io/packagist/v/ui-awesome/form-model.svg?style=for-the-badge&logo=packagist&logoColor=white&label=Stable)](https://packagist.org/packages/ui-awesome/form-model)
[![Total Downloads](https://img.shields.io/packagist/dt/ui-awesome/form-model.svg?style=for-the-badge&logo=composer&logoColor=white&label=Downloads)](https://packagist.org/packages/ui-awesome/form-model)

## Quality code

[![Codecov](https://img.shields.io/codecov/c/github/ui-awesome/form-model.svg?style=for-the-badge&logo=codecov&logoColor=white&label=Coverage)](https://codecov.io/github/ui-awesome/form-model)
[![PHPStan Level Max](https://img.shields.io/badge/PHPStan-Level%20Max-4F5D95.svg?style=for-the-badge&logo=github&logoColor=white)](https://github.com/ui-awesome/form-model/actions/workflows/static.yml)
[![StyleCI](https://img.shields.io/badge/StyleCI-Passed-44CC11.svg?style=for-the-badge&logo=github&logoColor=white)](https://github.styleci.io/repos/773961622?branch=main)

## Our social networks

[![Follow on X](https://img.shields.io/badge/-Follow%20on%20X-1DA1F2.svg?style=for-the-badge&logo=x&logoColor=white&labelColor=000000)](https://x.com/Terabytesoftw)

## License

[![License](https://img.shields.io/badge/License-BSD--3--Clause-brightgreen.svg?style=for-the-badge&logo=opensourceinitiative&logoColor=white&labelColor=555555)](LICENSE)
