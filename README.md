<!-- markdownlint-disable MD041 -->
<p align="center">
    <a href="https://github.com/ui-awesome/form-model" target="_blank">
        <img src="https://raw.githubusercontent.com/ui-awesome/.github/refs/heads/main/logo/ui_awesome.png" alt="UI Awesome" width="25%">
    </a>
    <h1 align="center">Form Model for PHP</h1>
    <br>
</p>
<!-- markdownlint-enable MD041 -->

<p align="center">
    <a href="https://github.com/ui-awesome/form-model/actions/workflows/build.yml" target="_blank">
        <img src="https://img.shields.io/github/actions/workflow/status/ui-awesome/form-model/build.yml?style=for-the-badge&label=PHPUnit&logo=github" alt="PHPUnit">
    </a>
    <a href="https://codecov.io/github/ui-awesome/form-model" target="_blank">
        <img src="https://img.shields.io/codecov/c/github/ui-awesome/form-model.svg?style=for-the-badge&logo=codecov&logoColor=white&label=Coverage" alt="Coverage">
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
    <em>Hints, labels, placeholders, field configuration, nested field metadata, and field-scoped error handling</em>
</p>

## Features

<picture>
    <source media="(max-width: 767px)" srcset="./docs/svgs/features-mobile.svg">
    <img src="./docs/svgs/features.svg" alt="Feature Overview" style="width: 100%;">
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

use UIAwesome\FormModel\BaseFormModel;
use UIAwesome\FormModel\Attribute\{FieldConfig, Hint, Label, Placeholder};

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

$form = new SignInForm();

$form->addError('email', 'Email is required.');
$errors = $form->getErrors();
/*
[
    'email' => ['Email is required.'],
]
*/

$summary = $form->getErrorSummary();
// ['Email is required.']

$label = $form->getLabel('email');
// 'Email address'
```

## Nested field metadata

You can request metadata using dot notation when a field contains another `BaseFormModel`.

```php
$hint = $form->getHint('profile.address.city');
$label = $form->getLabel('profile.address.city');
$placeholder = $form->getPlaceholder('profile.address.city');
$rules = $form->getRule('profile.address.city');
```

## Property attributes

Property attributes are resolved first for metadata (`Hint`, `Label`, `Placeholder`, `FieldConfig`).
Map methods (`getHints()`, `getLabels()`, `getPlaceholders()`, `getFieldConfigs()`) remain as fallback.

```php
$emailHint = $form->getHint('email');
$emailLabel = $form->getLabel('email');
$emailPlaceholder = $form->getPlaceholder('email');
$emailFieldConfig = $form->getFieldConfig('email');
```

## Error collection and first-error mode

Use first-error mode when you need one message per field.

```php
$form->setErrors(
    [
        'email' => ['Email is required.', 'Email is invalid.'],
        'password' => ['Password is required.'],
    ],
);

$firstErrors = $form->getFirstErrors();
/*
[
    'email' => 'Email is required.',
    'password' => 'Password is required.',
]
*/
```

## Documentation

For detailed configuration options and advanced usage.

- 📚 [Installation Guide](docs/installation.md)
- ⚙️ [Configuration Reference](docs/configuration.md)
- 💡 [Usage Examples](docs/examples.md)
- 🧪 [Testing Guide](docs/testing.md)
- 🔄 [Upgrade Guide](UPGRADE.md)

## AI assistance

AI agents: read the [documentation index](https://yii2-framework.github.io/docs/llms.txt) and fetch the Markdown version
of a page by appending `index.md` to its URL. The
[AI agents guide](https://yii2-framework.github.io/docs/project/ai-agents/) covers skill installation, client setup and
verification.

- 🤖 [`ui-awesome-form-model` skill](docs/agents.md): labels, hints, placeholders, field configuration, nested field
  paths and field-scoped errors.

Match the installed package revision. If hosted documentation does not match or is unavailable, use the
[repository guides](#documentation), the [skill source](skills/ui-awesome-form-model/SKILL.md), the source PHPDoc and
the [changelog](CHANGELOG.md). This package stores rules and errors but does not run validation, and it does not depend
on Yii; no Yii application skill is required.

## Package information

[![PHP](https://img.shields.io/badge/%3E%3D8.3-777BB4.svg?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/releases/8.3/en.php)
[![Latest Stable Version](https://img.shields.io/packagist/v/ui-awesome/form-model.svg?style=for-the-badge&logo=packagist&logoColor=white&label=Stable)](https://packagist.org/packages/ui-awesome/form-model)
[![Total Downloads](https://img.shields.io/packagist/dt/ui-awesome/form-model.svg?style=for-the-badge&logo=composer&logoColor=white&label=Downloads)](https://packagist.org/packages/ui-awesome/form-model)

## Project status

[![PHPStan Level Max](https://img.shields.io/badge/PHPStan-Level%20Max-4F5D95.svg?style=for-the-badge&logo=github&logoColor=white)](https://github.com/ui-awesome/form-model/actions/workflows/static.yml)
[![Quality](https://img.shields.io/github/actions/workflow/status/ui-awesome/form-model/quality.yml?style=for-the-badge&label=Quality&logo=github)](https://github.com/ui-awesome/form-model/actions/workflows/quality.yml)
[![StyleCI](https://img.shields.io/badge/dynamic/xml?url=https%3A%2F%2Fgithub.styleci.io%2Frepos%2F773961622%2Fshield%3Fbranch%3Dmain&query=%2F%2F*%5Blocal-name%28%29%3D%27text%27%5D%5B2%5D&label=StyleCI&style=for-the-badge&logo=github&logoColor=white&color=4F5D95)](https://github.styleci.io/repos/773961622?branch=main)

## Security

[![OpenSSF Scorecard](https://api.scorecard.dev/projects/github.com/ui-awesome/form-model/badge?style=for-the-badge)](https://scorecard.dev/viewer/?uri=github.com/ui-awesome/form-model)
[![Zizmor](https://img.shields.io/github/actions/workflow/status/ui-awesome/form-model/security.yml?style=for-the-badge&label=Zizmor&logo=github)](https://github.com/ui-awesome/form-model/actions/workflows/security.yml)

## Built with AI

[![Built with Claude Code](https://img.shields.io/badge/Built%20with-Claude%20Code-D97757.svg?style=for-the-badge&logo=claude&logoColor=white)](https://claude.com/claude-code)
[![Built with Codex](https://img.shields.io/badge/Built%20with-Codex-10A37F.svg?style=for-the-badge&logo=openaigym&logoColor=white)](https://openai.com/codex)
[![Reviewed by CodeRabbit](https://img.shields.io/badge/Reviewed%20by-CodeRabbit-FF570A.svg?style=for-the-badge&logo=coderabbit&logoColor=white)](https://www.coderabbit.ai)

## Our social networks

[![Follow on X](https://img.shields.io/badge/-Follow%20on%20X-1DA1F2.svg?style=for-the-badge&logo=x&logoColor=white&labelColor=000000)](https://x.com/Terabytesoftw)
[![Follow on Facebook](https://img.shields.io/badge/-Follow%20on%20Facebook-1877F2.svg?style=for-the-badge&logo=facebook&logoColor=white&labelColor=000000)](https://www.facebook.com/wilmer.arambula.9)

## License

[![License](https://img.shields.io/badge/License-BSD--3--Clause-brightgreen.svg?style=for-the-badge&logo=opensourceinitiative&logoColor=white&labelColor=555555)](LICENSE)
