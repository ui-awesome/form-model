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
    /**
     * Creates field label metadata.
     *
     * @param string $value Display label for the field.
     */
    public function __construct(
        /**
         * Display label for the field.
         */
        public string $value,
    ) {}
}
