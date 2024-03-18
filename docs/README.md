# Form Model

Is a library that provides a flexible and easy way to create and validate fields for web forms, models, and other data
structures.

## Usage

To use this library, you need to create a class that extends the AbstractFormModel class and define the fields you want
to use. For example:

```php
<?php

declare(strict_types=1);

namespace App\FormModel;

use UIAwesome\FormModel\AbstractFormModel;

final class User extends AbstractFormModel
{
    private string $username = '';
    private string $password = '';
    private string $email = '';
}
```

[Operations basic with model](https://github.com/php-forge/model/blob/main/docs/README.md)

Operations on the field model are performed using the methods provided by the `AbstractFormModel::class`.

- [Add property error](#add-property-error)
- [Clear errors](#clear-errors)
- [Get error summary](#get-error-summary)
- [Get errors](#get-errors)
- [Get hint by property](#get-hint-by-property)
- [Get hints](#get-hints)
- [Get label by property](#get-label-by-property)
- [Get labels](#get-labels)
- [Get placeholder by property](#get-placeholder-by-property)
- [Get placeholders](#get-placeholders)
- [Get property error](#get-property-error)
- [Get rules](#get-rules)
- [Get rules by property](#get-rules-by-property)
- [Has property error](#has-property-error)
- [Has property validate](#has-property-validate)

### Add property error

The `addPropertyError` method is used to add an error to a property.

The method accepts two parameters:

- property: The name of the property to which the error is to be added.
- error: The error message to be added.

```php
<?php

declare(strict_types=1);

use App\FormModel\User;

$formModel = new User();

$formModel->addPropertyError('username', 'The username is required.');
```

### Clear errors

The `clearErrors` method is used to clear all errors.

The method accepts one parameters:

- property: The name of the property to clear errors.

```php
<?php

declare(strict_types=1);

use App\FormModel\User;

$formModel = new User();

// Add error to property
$formModel->addPropertyError('username', 'The username is required.');

// Clear errors
$formModel->clearErrors();
```

Clean errors for a specific property.

```php
<?php

declare(strict_types=1);

use App\FormModel\User;

$formModel = new User();

// Add error to property
$formModel->addPropertyError('username', 'The username is required.');

// Clear errors
$formModel->clearErrors('username');
```

### Get error summary

The `getErrorSummary` method is used to get the error of every property in the collection.

The method accepts one parameters.

- onlyProperties: List of properties to return errors. For default is `[]`.
- first: Whether to return only the first error of each property.

```php
<?php

declare(strict_types=1);

use App\FormModel\User;

$formModel = new User();

// Add error to property
$formModel->addPropertyError('username', 'The username is required.');
$formModel->addPropertyError('password', 'The password is required.');
$formModel->addPropertyError('email', 'The email is required.');

// Get error summary
$errors = $formModel->getErrorSummary();
```

### Get errors

The `getErrors` method is used to get all errors for all properties.

The method accepts one parameters.

- first: Whether to return only the first error of each property.

```php
<?php

declare(strict_types=1);

use App\FormModel\User;

$formModel = new User();

// Add error to property
$formModel->addPropertyError('username', 'The username is required.');
$formModel->addPropertyError('password', 'The password is required.');
$formModel->addPropertyError('email', 'The email is required.');

// Get errors

$errors = $formModel->getErrors();
```

### Get hint by property

The `getHintByProperty` method is used to get the text hint for the specified property.

The method accepts one parameters.

- property: The name of the property.

```php
<?php

declare(strict_types=1);

use App\FormModel\User;

$formModel = new User();

// Get the text hint for the specified property
$hint = $formModel->getHintByProperty('username');
```

### Get hints

The `getHints` method is used to allows defining hints for the field model propierties in associative array format.

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
}
```

### Get label by property

The `getLabelByProperty` method is used to get the label for the specified property.

The method accepts one parameters.

- property: The name of the property.

```php
<?php

declare(strict_types=1);

use App\FormModel\User;

$formModel = new User();

// Get the label for the specified property
$label = $formModel->getLabelByProperty('username');
```

### Get labels

The `getLabels` method is used to allows defining labels for the field model propierties in associative array format.

```php
<?php

declare(strict_types=1);

namespace App\FormModel;

use UIAwesome\FormModel\AbstractFormModel;

final class User extends AbstractFormModel
{
    private string $username = '';

    public function getLabels(): array
    {
        return [
            'username' => 'The label for the field.',
        ];
    }
}
```

### Ger placeholder by property

The `getPlaceholderByProperty` method is used to get the placeholder for the specified property.

The method accepts one parameters.

- property: The name of the property.

```php
<?php

declare(strict_types=1);

use App\FormModel\User;

$formModel = new User();

// Get the placeholder for the specified property
$placeholder = $formModel->getPlaceholderByProperty('username');
```

### Get placeholders

The `getPlaceholders` method is used to allows defining placeholders for the field model propierties in associative
array format.

```php
<?php

declare(strict_types=1);

namespace App\FormModel;

use UIAwesome\FormModel\AbstractFormModel;

final class User extends AbstractFormModel
{
    private string $username = '';

    public function getPlaceholders(): array
    {
        return [
            'username' => 'The placeholder for the field.',
        ];
    }
}
```

### Get property error

The `getPropertyError` method is used to get the errors for a single property.

The method accepts two parameters.

- property: The name of the property.
- first: Whether to return only the first error of each property.

```php
<?php

declare(strict_types=1);

use App\FormModel\User;

$formModel = new User();

// Add error to property
$formModel->addPropertyError('username', 'The username is required.');

// Get the errors for a single property
$error = $formModel->getPropertyError('username');
```

### Get rules

The `getRules` method is used to get a set of validation rules.

```php
<?php

declare(strict_types=1);

namespace App\FormModel;

use UIAwesome\FormModel\AbstractFormModel;

final class User extends AbstractFormModel
{
    private string $username = '';

    public function getRules(): array
    {
        return [
            'username' => [
                // your rules
            ],
        ];
    }
}
```

### Get rules by property

The `getRulesByProperty` method is used to get the validation rules for the specified property.

The method accepts one parameters.

- property: The name of the property.

```php
<?php

declare(strict_types=1);

use App\FormModel\User;

$formModel = new User();

// Get the validation rules for the specified property
$rules = $formModel->getRulesByProperty('username');
```

### Has property error

The `hasPropertyError` method is used to check if the property has errors.

The method accepts one parameters.

- property: The name of the property.

```php
<?php

declare(strict_types=1);

use App\FormModel\User;

$formModel = new User();

// Add error to property
$formModel->addPropertyError('username', 'The username is required.');

// Check if the property has errors
$hasError = $formModel->hasPropertyError('username');
```

### Has property validate

The `hasPropertyValidate` method is used to check if the property has validate.

The method accepts one parameters.

- property: The name of the property.

```php
<?php

declare(strict_types=1);

use App\FormModel\User;

$formModel = new User();

// Check if the property has validate
$hasValidate = $formModel->hasPropertyValidate('username');
```

## Methods

Refer to the [Tests](https://github.com/php-forge/field-model/blob/main/tests) for comprehensive examples.

The following methods are available for setting and retrieving model data.

| Method                        | Description                                                                          |
| ----------------------------- | ------------------------------------------------------------------------------------ |
| `addPropertyError()`          | Add error to property.                                                               |
| `clearErrors()`               | Clear errors.                                                                        |
| `getErrorSummary()`           | Get the error of every property in the collection.                                   |
| `getErrors()`                 | Get all errors for all properties.                                                   |
| `getHintByProperty()`         | Get the text hint for the specified property.                                        |
| `getHints()`                  | Allows defining hints for the field model propierties in associative array format.   |
| `getLabelByProperty()`        | Get the label for the specified property.                                            |
| `getLabels()`                 | Allows defining labels for the field model propierties in associative array format.  |
| `getPlaceholderByProperty()`  | Get the placeholder for the specified property.                                      |
| `getPlaceholders()`           | Allows defining placeholders for the field model propierties in associative array    |
|                               | format.                                                                              |
| `getPropertyError()`          | Get the errors for a single property.                                                |
| `getRules()`                  | A set of validation rules.                                                           | 
| `getRulesByProperty()`        | The validation rules for the specified property.                                     |
| `hasPropertyError()`          | Check if the property has errors.                                                    |
| `hasPropertyValidate()`       | Check if the property has validate.                                                  |
| `setErrors()`                 | Set errors for multiple properties.                                                  |
