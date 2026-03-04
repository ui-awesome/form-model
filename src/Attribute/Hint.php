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
 *
 * @copyright Copyright (C) 2026 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Hint
{
    public function __construct(public readonly string $value) {}
}
