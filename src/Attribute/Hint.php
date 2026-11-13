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
    /**
     * Creates field hint metadata.
     *
     * @param string $value Hint text for the field.
     */
    public function __construct(
        /**
         * Hint text for the field.
         */
        public string $value,
    ) {}
}
