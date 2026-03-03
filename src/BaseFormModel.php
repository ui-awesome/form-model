<?php

declare(strict_types=1);

namespace UIAwesome\FormModel;

use PHPForge\Helper\WordCaseConverter;
use UIAwesome\Model\BaseModel;

/**
 * Base implementation of {@see FormModelInterface}.
 *
 * Usage example:
 * ```php
 * final class UserForm extends BaseFormModel
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
abstract class BaseFormModel extends BaseModel implements FormModelInterface
{
    /**
     * Lazily initialized field-error storage.
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

    public function clearError(string|null $field = null): void
    {
        $this->error()->clear($field);
    }

    public function getError(string $field): array
    {
        return $this->error()->getForField($field);
    }

    /**
     * @phpstan-return array<string, array<int, string>>
     */
    public function getErrors(): array
    {
        return $this->error()->getAll();
    }

    /**
     * @phpstan-param list<string> $onlyFields
     * @phpstan-return array<array-key, string>
     */
    public function getErrorSummary(array $onlyFields = [], bool $first = false): array
    {
        return $this->error()->getSummary($onlyFields, $first);
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

    public function getFieldConfigs(): array
    {
        return [];
    }

    public function getFirstError(string $field): string
    {
        return $this->error()->getFirstForField($field);
    }

    /**
     * @phpstan-return array<string, string>
     */
    public function getFirstErrors(): array
    {
        return $this->error()->getAllFirst();
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

    /**
     * @phpstan-return array<mixed, mixed>|null
     */
    public function getRule(string $field): array|null
    {
        $rule = $this->metadata()->get('getRules', 'getRule', $field);

        return is_array($rule) ? $rule : null;
    }

    /**
     * @phpstan-return iterable<string, array<mixed, mixed>>
     */
    public function getRules(): iterable
    {
        return [];
    }

    public function hasError(string|null $field = null): bool
    {
        return $this->error()->has($field);
    }

    public function isValidated(string $field): bool
    {
        return $this->error()->isValidated($field);
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
