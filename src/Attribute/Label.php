<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Attribute;

use Attribute;

/**
 * Defines a display label for a form-model property.
 *
 * Usage example:
 * ```php
 * #[Label('Email address')]
 * public string $email = '';
 * ```
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class Label
{
    public function __construct(public string $value) {}
}
