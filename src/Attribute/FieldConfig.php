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
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class FieldConfig
{
    /**
     * @param array<int|string, mixed> $value
     */
    public function __construct(public array $value) {}
}
