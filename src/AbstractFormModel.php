<?php

declare(strict_types=1);

namespace UIAwesome\FormModel;

use PHPForge\Helper\WordCaseConverter;
use UIAwesome\Model\AbstractModel;

/**
 * Base implementation of {@see FormModelInterface}.
 *
 * Usage example:
 * ```php
 * final class UserForm extends AbstractFormModel
 * {
 *     public string $email = '';
 * }
 *
 * $form = new UserForm();
 * $form->addError('email', 'Email is invalid.');
 * ```
 *
 * @copyright Copyright (C) 2024 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
abstract class AbstractFormModel extends AbstractModel implements FormModelInterface
{
    /**
     * Lazily initialized property-error storage.
     */
    private FieldError|null $fieldError = null;

    /**
     * Lazily initialized field-metadata resolver.
     */
    private FieldMetadata|null $fieldMetadata = null;

    public function addError(string $field, string $error): void
    {
        $this->error()->add($field, $error);
    }

    public function clearError(string|null $property = null): void
    {
        $this->error()->clear($property);
    }

    /**
     * @phpstan-return array<string, array<int, string>>
     */
    public function getErrors(): array
    {
        /** @phpstan-var array<string, array<int, string>> $errors */
        $errors = $this->error()->get();

        return $errors;
    }

    /**
     * @phpstan-return array<string, string>
     */
    public function getFirstErrors(): array
    {
        /** @phpstan-var array<string, string> $errors */
        $errors = $this->error()->get(true);

        return $errors;
    }

    /**
     * @phpstan-param list<string> $onlyProperties
     * @phpstan-return array<array-key, string>
     */
    public function getErrorSummary(array $onlyProperties = [], bool $first = false): array
    {
        return $this->error()->getSummary($onlyProperties, $first);
    }

    public function getFieldConfigs(): array
    {
        return [];
    }

    /**
     * @phpstan-return array<int|string, mixed>
     */
    public function getFieldConfig(string $field): array
    {
        $fieldConfig = $this->metadata()->get(
            'getFieldConfigs',
            'getFieldConfig',
            $field,
            [],
        );

        return is_array($fieldConfig) ? $fieldConfig : [];
    }

    public function getHint(string $field): string
    {
        $hint = $this->metadata()->get('getHints', 'getHint', $field);

        return is_string($hint) ? $hint : '';
    }

    public function getHints(): array
    {
        return [];
    }

    public function getLabel(string $field): string
    {
        $generateLabel = WordCaseConverter::toTitleWords($field);
        $label = $this->metadata()->get('getLabels', 'getLabel', $field, $generateLabel);

        return is_string($label) ? $label : '';
    }

    public function getLabels(): array
    {
        return [];
    }

    public function getPlaceholder(string $field): string
    {
        $placeholder = $this->metadata()->get('getPlaceholders', 'getPlaceholder', $field);

        return is_string($placeholder) ? $placeholder : '';
    }

    public function getPlaceholders(): array
    {
        return [];
    }

    public function getError(string $field): array
    {
        /** @phpstan-var array<int, string> $errors */
        $errors = $this->error()->getProperty($field);

        return $errors;
    }

    public function getFirstError(string $field): string
    {
        /** @phpstan-var string $error */
        $error = $this->error()->getProperty($field, true);

        return $error;
    }

    /**
     * @phpstan-return iterable<string, array<mixed, mixed>>
     */
    public function getRules(): iterable
    {
        return [];
    }

    /**
     * @phpstan-return array<mixed, mixed>|null
     */
    public function getRule(string $field): array|null
    {
        $rule = $this->metadata()->get('getRules', 'getRule', $field);

        return is_array($rule) ? $rule : null;
    }

    public function hasError(string|null $field = null): bool
    {
        return $this->error()->has($field);
    }

    public function isValidated(string $field): bool
    {
        return $this->error()->hasValidate($field);
    }

    public function setErrors(array $values): void
    {
        $this->error()->set($values);
    }

    /**
     * Returns the lazily initialized field error storage.
     *
     * @return FieldError Field error storage instance.
     */
    private function error(): FieldError
    {
        if ($this->fieldError === null) {
            $this->fieldError = new FieldError();
        }

        return $this->fieldError;
    }

    /**
     * Returns the lazily initialized metadata resolver.
     *
     * @return FieldMetadata Metadata resolver instance.
     */
    private function metadata(): FieldMetadata
    {
        if ($this->fieldMetadata === null) {
            $this->fieldMetadata = new FieldMetadata($this);
        }

        return $this->fieldMetadata;
    }
}
