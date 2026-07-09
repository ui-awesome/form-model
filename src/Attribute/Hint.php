<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Attribute;

use Attribute;

/**
 * Defines hint text for a form-model property.
 *
 * Usage example:
 * ```php
 * #[Hint('Used for account recovery notifications.')]
 * public string $email = '';
 * ```
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class Hint
{
    public function __construct(public string $value) {}
}
