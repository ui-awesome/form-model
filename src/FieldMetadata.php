<?php

declare(strict_types=1);

namespace UIAwesome\FormModel;

use InvalidArgumentException;

use function explode;
use function str_contains;

/**
 * FieldMetadata is the base class for field metadata handling in forms.
 */
final class FieldMetadata
{
    public function __construct(private FormModelInterface $formModel) {}

    public function get(
        string $method,
        string $methodNested,
        string $property,
        array|string $defaultValue = ''
    ): mixed {
        [$property, $nested] = $this->getNestedMetadata($property);

        if ($nested !== null) {
            $nestedValue = $this->formModel->getPropertyValue($property);

            if ($nestedValue instanceof FormModelInterface) {
                return $nestedValue->$methodNested($nested);
            }
        }

        return $this->formModel->$method()[$property] ?? $defaultValue;
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
     * @psalm-return array{0: string, 1: null|string}
     */
    private function getNestedMetadata(string $property): array
    {
        if (str_contains($property, '.')) {
            $result = explode('.', $property, 2);

            return [$result[0], $result[1] ?? null];
        }

        return [$property, null];
    }
}
