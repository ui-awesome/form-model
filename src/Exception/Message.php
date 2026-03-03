<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Exception;

use function sprintf;

/**
 * Represents reusable error message templates for form-model exceptions.
 *
 * Use {@see Message::getMessage()} to format template values via `sprintf()` placeholders.
 *
 * @copyright Copyright (C) 2026 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
enum Message: string
{
    /**
     * Indicates invalid dot-notated nested field path syntax.
     *
     * Format: "Invalid nested field path format: '%s'."
     */
    case INVALID_NESTED_FIELD_PATH = "Invalid nested field path format: '%s'.";

    /**
     * Indicates an unknown top-level metadata method.
     *
     * Format: "Unknown metadata method: '%s'."
     */
    case UNKNOWN_METADATA_METHOD = "Unknown metadata method: '%s'.";

    /**
     * Indicates an unknown nested metadata method.
     *
     * Format: "Unknown nested metadata method: '%s'."
     */
    case UNKNOWN_NESTED_METADATA_METHOD = "Unknown nested metadata method: '%s'.";

    /**
     * Returns the formatted message string for the error case.
     *
     * Usage example:
     * ```php
     * throw new InvalidArgumentException(
     *     \UIAwesome\FormModel\Exception\Message::UNKNOWN_METADATA_METHOD->getMessage($methodName)
     * );
     * ```
     *
     * @param int|string ...$argument Values to insert into the message template.
     *
     * @return string Formatted error message with interpolated arguments.
     */
    public function getMessage(int|string ...$argument): string
    {
        return sprintf($this->value, ...$argument);
    }
}
