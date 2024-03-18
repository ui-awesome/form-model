<p align="center">
    <a href="https://github.com/ui-awesome/form-model" target="_blank">
        <img src="https://avatars.githubusercontent.com/u/121752654?s=200&v=4" height="100px">
    </a>
    <h1 align="center">UIAwesome Form Model for PHP.</h1>
    <br>
</p>

<p align="center">
    <a href="https://github.com/ui-awesome/form-model/actions/workflows/build.yml" target="_blank">
        <img src="https://github.com/ui-awesome/form-model/actions/workflows/build.yml/badge.svg" alt="PHPUnit">
    </a>
    <a href="https://codecov.io/gh/ui-awesome/form-model" target="_blank">
        <img src="https://codecov.io/gh/ui-awesome/form-model/branch/main/graph/badge.svg?token=MF0XUGVLYC" alt="Codecov">
    </a>
    <a href="https://dashboard.stryker-mutator.io/reports/github.com/ui-awesome/form-model/main" target="_blank">
        <img src="https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fui-awesome%2Fform-model%2Fmain" alt="Infection">
    </a>
    <a href="https://github.com/ui-awesome/form-model/actions/workflows/static.yml" target="_blank">
        <img src="https://github.com/ui-awesome/form-model/actions/workflows/static.yml/badge.svg" alt="Psalm">
    </a>
    <a href="https://shepherd.dev/github/ui-awesome/form-model" target="_blank">
        <img src="https://shepherd.dev/github/ui-awesome/form-model/coverage.svg" alt="Psalm Coverage">
    </a>
    <a href="https://github.styleci.io/repos/773961622?branch=main">
        <img src="https://github.styleci.io/repos/773961622/shield?branch=main" alt="Style ci">
    </a>    
</p>

Is a library that provides a flexible and easy way to create and validate fields for web forms, models, and other data
structures.

```php
<?php

declare(strict_types=1);

namespace App\FormModel;

use UIAwesome\FormModel\AbstractFormModel;

final class User extends AbstractFormModel
{
    private string $username = '';

    public function getHints(): array
    {
        return [
            'username' => 'The hint for the field.',
        ];
    }
    
    public function getLabels(): array
    {
        return [
            'username' => 'The label for the field.',
        ];
    }

    public function getPlaceholders(): array
    {
        return [
            'username' => 'The placeholder for the field.',
        ];
    }
}
```

## Installation

The preferred way to install this extension is through [composer](https://getcomposer.org/download/).

Either run

```shell
composer require --prefer-dist ui-awesome/form-model:"^0.1"
```

or add

```json
"ui-awesome/form-model": "^0.1"
```

## Usage

[Check the documentation docs](docs/README.md) to learn about usage.

## Testing

[Check the documentation testing](docs/testing.md) to learn about testing.

## Support versions

[![PHP81](https://img.shields.io/badge/PHP-%3E%3D8.1-787CB5)](https://www.php.net/releases/8.1/en.php)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Our social networks

[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/Terabytesoftw)
