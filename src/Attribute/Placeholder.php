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
 *
 * @copyright Copyright (C) 2026 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Placeholder
{
    public function __construct(public readonly string $value) {}
}
