<?php

declare(strict_types=1);

namespace UIAwesome\FormModel;

use InvalidArgumentException;
use Traversable;

use function explode;
use function iterator_to_array;
use function str_contains;
use function trim;

/**
 * Resolves field metadata for form model fields, including nested fields.
 *
 * Usage example:
 * ```php
 * $metadata = new FieldMetadata($form);
 * $emailLabel = $metadata->get('getLabels', 'getLabel', 'email');
 * ```
 *
 * @copyright Copyright (C) 2024 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class FieldMetadata
{
    /**
     * @param FormModelInterface $formModel Form model used to resolve metadata values.
     */
    public function __construct(private readonly FormModelInterface $formModel) {}

    /**
     * Returns metadata for a field using direct or nested resolution.
     *
     * Usage example:
     * ```php
     * $label = $metadata->get('getLabels', 'getLabel', 'email');
     * $nestedLabel = $metadata->get('getLabels', 'getLabel', 'profile.email');
     * ```
     *
     * @param string $method Method used to retrieve top-level metadata maps.
     * @param string $methodNested Method used to retrieve nested metadata values.
     * @param string $fieldPath Field path, optionally in dot notation.
     * @param array|string $defaultValue Default value returned when metadata is unavailable.
     *
     * @phpstan-param array<int|string, mixed>|string $defaultValue
     */
    public function get(
        string $method,
        string $methodNested,
        string $fieldPath,
        array|string $defaultValue = '',
    ): mixed {
        [$field, $nested] = $this->getNestedMetadata($fieldPath);

        if ($nested !== null) {
            return $this->getNestedValue($methodNested, $field, $nested, $defaultValue);
        }

        $metadata = $this->getMetadataByMethod($method);

        return $metadata[$field] ?? $defaultValue;
    }

    /**
     * Returns metadata mapped by a supported form-model method.
     *
     * @phpstan-return array<int|string, mixed>
     */
    private function getMetadataByMethod(string $method): array
    {
        $metadata = match ($method) {
            'getHints' => $this->formModel->getHints(),
            'getLabels' => $this->formModel->getLabels(),
            'getPlaceholders' => $this->formModel->getPlaceholders(),
            'getRules' => $this->formModel->getRules(),
            'getFieldConfigs' => $this->formModel->getFieldConfigs(),
            default => throw new InvalidArgumentException("Unknown metadata method: {$method}."),
        };

        if ($metadata instanceof Traversable) {
            return iterator_to_array($metadata);
        }

        return $metadata;
    }

    /**
     * Splits a field path into parent and nested segments.
     *
     * @param string $fieldPath Field path, optionally in dot notation.
     *
     * @throws InvalidArgumentException If the nested field path format is invalid.
     *
     * @return array Array with parent field and nested field, or `null` when not nested.
     *
     * @phpstan-return array{0: string, 1: null|string}
     */
    private function getNestedMetadata(string $fieldPath): array
    {
        if (str_contains($fieldPath, '.')) {
            $result = explode('.', $fieldPath, 2);
            $parentField = $result[0];
            $nestedField = $result[1] ?? '';

            if (trim($parentField) === '' || trim($nestedField) === '') {
                throw new InvalidArgumentException("Invalid nested field path format: {$fieldPath}.");
            }

            return [$parentField, $nestedField];
        }

        return [$fieldPath, null];
    }

    /**
     * Returns metadata for a nested field on a nested form model.
     *
     * @param string $methodNested Method used to query nested metadata.
     * @param string $field Parent field name.
     * @param string $nested Nested field name.
     * @param array|string $defaultValue Default value returned when nested metadata is unavailable.
     *
     * @phpstan-param array<int|string, mixed>|string $defaultValue
     */
    private function getNestedValue(
        string $methodNested,
        string $field,
        string $nested,
        array|string $defaultValue,
    ): mixed {
        $nestedValue = $this->formModel->getValue($field);

        if (!$nestedValue instanceof FormModelInterface) {
            return $defaultValue;
        }

        return match ($methodNested) {
            'getHint' => $nestedValue->getHint($nested),
            'getLabel' => $nestedValue->getLabel($nested),
            'getPlaceholder' => $nestedValue->getPlaceholder($nested),
            'getRule' => $nestedValue->getRule($nested),
            'getFieldConfig' => $nestedValue->getFieldConfig($nested),
            default => throw new InvalidArgumentException("Unknown nested metadata method: {$methodNested}."),
        };
    }
}
