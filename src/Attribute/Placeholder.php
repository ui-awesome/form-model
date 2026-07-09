<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Attribute;

use Attribute;

/**
 * Defines placeholder text for a form-model property.
 *
 * Usage example:
 * ```php
 * #[Placeholder('name@example.com')]
 * public string $email = '';
 * ```
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class Placeholder
{
    public function __construct(public string $value) {}
}
