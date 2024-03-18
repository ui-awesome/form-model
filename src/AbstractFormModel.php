<?php

declare(strict_types=1);

namespace UIAwesome\FormModel;

use PHPForge\Helper\WordFormatter;
use UIAwesome\{Html\Interop\InputInterface, Model\AbstractModel};

abstract class AbstractFormModel extends AbstractModel implements FormModelInterface
{
    private FieldMetadata|null $fieldMetadata = null;
    private FieldError|null $fieldError = null;

    public function addPropertyError(string $property, string $error): void
    {
        $this->error()->add($property, $error);
    }

    public function applyToHtmlRulesByProperty(InputInterface $input, string $property): InputInterface
    {
        return $input;
    }

    public function clearError(string $property = null): void
    {
        $this->error()->clear($property);
    }

    public function getErrorSummary(array $onlyProperties = [], bool $first = false): array
    {
        return $this->error()->getSummary($onlyProperties, $first);
    }

    public function getErrors(bool $first = false): array
    {
        return $this->error()->get($first);
    }

    public function getPropertyError(string $property, bool $first = false): array|string
    {
        return $this->error()->getProperty($property, $first);
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
        $generateLabel = WordFormatter::capitalizeToWords($property);
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

    public function getRules(): iterable
    {
        return [];
    }

    public function getRulesByProperty(string $property): array|null
    {
        $ruleByProperty = $this->metadata()->get('getRules', 'getRulesByProperty', $property);

        return is_array($ruleByProperty) ? $ruleByProperty : null;
    }

    public function getWidgetConfig(): array
    {
        return [];
    }

    public function getWidgetConfigByClass(string $class): array
    {
        $widgetConfigByClass = $this->metadata()->get(
            'getWidgetConfig',
            'getWidgetConfigByClass',
            $class,
            [],
        );

        return is_array($widgetConfigByClass) ? $widgetConfigByClass : [];
    }

    public function getWidgetConfigByProperty(string $property): array
    {
        $widgetConfigByProperty = $this->metadata()->get(
            'getWidgetConfigByProperties',
            'getWidgetConfigByProperty',
            $property,
            [],
        );

        return is_array($widgetConfigByProperty) ? $widgetConfigByProperty : [];
    }

    public function getWidgetConfigByProperties(): array
    {
        return [];
    }

    public function hasPropertyError(string $property = null): bool
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
