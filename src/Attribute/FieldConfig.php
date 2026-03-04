<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Attribute;

use Attribute;

/**
 * Defines field configuration options for a form-model property.
 *
 * Usage example:
 * ```php
 * #[FieldConfig(['class' => ['input', 'input-bordered']])]
 * public string $email = '';
 * ```
 *
 * @copyright Copyright (C) 2026 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class FieldConfig
{
    /**
     * @param array<int|string, mixed> $value
     */
    public function __construct(public readonly array $value) {}
}
