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
 * FieldMetadata is the base class for field metadata handling in forms.
 */
final class FieldMetadata
{
    public function __construct(private readonly FormModelInterface $formModel) {}

    /**
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
     * Extracts the nested property metadata from the given property string.
     *
     * @param string $property The property name.
     *
     * @throws InvalidArgumentException If the property string is invalid.
     *
     * @return array An array containing the property name and the nested property name (or null if not nested).
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
