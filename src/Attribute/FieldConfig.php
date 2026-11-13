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
     * Creates field configuration metadata.
     *
     * @param array<string, mixed> $value Method names mapped to the argument, or list of arguments, applied to the
     * field or to the widget that renders it.
     */
    public function __construct(
        /**
         * Method names mapped to field or widget arguments.
         *
         * @var array<string, mixed>
         */
        public array $value,
    ) {}
}
