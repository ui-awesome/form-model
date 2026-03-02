<?php

declare(strict_types=1);

namespace UIAwesome\FormModel;

use PHPForge\Helper\WordCaseConverter;
use UIAwesome\Model\AbstractModel;

abstract class AbstractFormModel extends AbstractModel implements FormModelInterface
{
    private FieldError|null $fieldError = null;
    private FieldMetadata|null $fieldMetadata = null;

    public function addPropertyError(string $property, string $error): void
    {
        $this->error()->add($property, $error);
    }

    public function clearError(string|null $property = null): void
    {
        $this->error()->clear($property);
    }

    /**
     * @phpstan-return array<string, array<int, string>|string>
     */
    public function getErrors(bool $first = false): array
    {
        return $this->error()->get($first);
    }

    /**
     * @phpstan-param list<string> $onlyProperties
     * @phpstan-return array<array-key, string>
     */
    public function getErrorSummary(array $onlyProperties = [], bool $first = false): array
    {
        return $this->error()->getSummary($onlyProperties, $first);
    }

    public function getHintByProperty(string $property): string
    {
        $hintByProperty = $this->metadata()->get('getHints', 'getHintByProperty', $property);

        return is_string($hintByProperty) ? $hintByProperty : '';
    }

    public function getHints(): array
    {
        return [];
    }

    public function getLabelByProperty(string $property): string
    {
        $generateLabel = WordCaseConverter::toTitleWords($property);
        $labelByProperty = $this->metadata()->get('getLabels', 'getLabelByProperty', $property, $generateLabel);

        return is_string($labelByProperty) ? $labelByProperty : '';
    }

    public function getLabels(): array
    {
        return [];
    }

    public function getPlaceholderByProperty(string $property): string
    {
        $placeholderByProperty = $this->metadata()->get('getPlaceholders', 'getPlaceholderByProperty', $property);

        return is_string($placeholderByProperty) ? $placeholderByProperty : '';
    }

    public function getPlaceholders(): array
    {
        return [];
    }

    public function getPropertyError(string $property, bool $first = false): array|string
    {
        return $this->error()->getProperty($property, $first);
    }

    /**
     * @phpstan-return iterable<string, mixed[]>
     */
    public function getRules(): iterable
    {
        return [];
    }

    /**
     * @phpstan-return mixed[]|null
     */
    public function getRulesByProperty(string $property): array|null
    {
        $ruleByProperty = $this->metadata()->get('getRules', 'getRulesByProperty', $property);

        return is_array($ruleByProperty) ? $ruleByProperty : null;
    }

    /**
     * @phpstan-return array<string, array<string, array<int, string>>>
     */
    public function getFieldConfigByProperties(): array
    {
        return [];
    }

    /**
     * @phpstan-return array<int|string, mixed>
     */
    public function getFieldConfigByProperty(string $property): array
    {
        $fieldConfigByProperty = $this->metadata()->get(
            'getFieldConfigByProperties',
            'getFieldConfigByProperty',
            $property,
            [],
        );

        return is_array($fieldConfigByProperty) ? $fieldConfigByProperty : [];
    }

    public function hasPropertyError(string|null $property = null): bool
    {
        return $this->error()->has($property);
    }

    public function hasPropertyValidate(string $property): bool
    {
        return $this->error()->hasValidate($property);
    }

    public function setErrors(array $values): void
    {
        $this->error()->set($values);
    }

    private function error(): FieldError
    {
        if ($this->fieldError === null) {
            $this->fieldError = new FieldError();
        }

        return $this->fieldError;
    }

    private function metadata(): FieldMetadata
    {
        if ($this->fieldMetadata === null) {
            $this->fieldMetadata = new FieldMetadata($this);
        }

        return $this->fieldMetadata;
    }
}
