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
 * Resolves field metadata for form model properties, including nested properties.
 *
 * Usage example:
 * ```php
 * $metadata = new FieldMetadata($form);
 * $emailLabel = $metadata->get('getLabels', 'getLabelByProperty', 'email');
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
     * Returns metadata for a property using direct or nested resolution.
     *
     * Usage example:
     * ```php
     * $label = $metadata->get('getLabels', 'getLabelByProperty', 'email');
     * $nestedLabel = $metadata->get('getLabels', 'getLabelByProperty', 'profile.email');
     * ```
     *
     * @param string $method Method used to retrieve top-level metadata maps.
     * @param string $methodNested Method used to retrieve nested metadata values.
     * @param string $property Property name, optionally in dot notation.
     * @param array|string $defaultValue Default value returned when metadata is unavailable.
     *
     * @phpstan-param array<int|string, mixed>|string $defaultValue
     */
    public function get(
        string $method,
        string $methodNested,
        string $property,
        array|string $defaultValue = '',
    ): mixed {
        [$property, $nested] = $this->getNestedMetadata($property);

        if ($nested !== null) {
            return $this->getNestedValue($methodNested, $property, $nested, $defaultValue);
        }

        $metadata = $this->getMetadataByMethod($method);

        return $metadata[$property] ?? $defaultValue;
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
            'getFieldConfigByProperties' => $this->formModel->getFieldConfigByProperties(),
            default => throw new InvalidArgumentException("Unknown metadata method: {$method}."),
        };

        if ($metadata instanceof Traversable) {
            return iterator_to_array($metadata);
        }

        return $metadata;
    }

    /**
     * Splits a property path into parent and nested segments.
     *
     * @param string $property Property name, optionally in dot notation.
     *
     * @throws InvalidArgumentException If the nested property format is invalid.
     *
     * @return array Array with parent property and nested property, or `null` when not nested.
     *
     * @phpstan-return array{0: string, 1: null|string}
     */
    private function getNestedMetadata(string $property): array
    {
        if (str_contains($property, '.')) {
            $result = explode('.', $property, 2);
            $parentProperty = $result[0];
            $nestedProperty = $result[1] ?? '';

            if (trim($parentProperty) === '' || trim($nestedProperty) === '') {
                throw new InvalidArgumentException("Invalid nested property format: {$property}.");
            }

            return [$parentProperty, $nestedProperty];
        }

        return [$property, null];
    }

    /**
     * Returns metadata for a nested property on a nested form model.
     *
     * @param string $methodNested Method used to query nested metadata.
     * @param string $property Parent property name.
     * @param string $nested Nested property name.
     * @param array|string $defaultValue Default value returned when nested metadata is unavailable.
     *
     * @phpstan-param array<int|string, mixed>|string $defaultValue
     */
    private function getNestedValue(
        string $methodNested,
        string $property,
        string $nested,
        array|string $defaultValue,
    ): mixed {
        $nestedValue = $this->formModel->getPropertyValue($property);

        if (!$nestedValue instanceof FormModelInterface) {
            return $defaultValue;
        }

        return match ($methodNested) {
            'getHintByProperty' => $nestedValue->getHintByProperty($nested),
            'getLabelByProperty' => $nestedValue->getLabelByProperty($nested),
            'getPlaceholderByProperty' => $nestedValue->getPlaceholderByProperty($nested),
            'getRulesByProperty' => $nestedValue->getRulesByProperty($nested),
            'getFieldConfigByProperty' => $nestedValue->getFieldConfigByProperty($nested),
            default => throw new InvalidArgumentException("Unknown nested metadata method: {$methodNested}."),
        };
    }
}
