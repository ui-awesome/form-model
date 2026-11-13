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
    /**
     * Creates field placeholder metadata.
     *
     * @param string $value Placeholder text for the field.
     */
    public function __construct(
        /**
         * Placeholder text for the field.
         */
        public string $value,
    ) {}
}
