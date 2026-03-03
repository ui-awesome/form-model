<?php

declare(strict_types=1);

namespace UIAwesome\FormModel;

use InvalidArgumentException;
use PHPForge\Helper\WordCaseConverter;
use Traversable;
use UIAwesome\FormModel\Exception\Message;
use UIAwesome\Model\BaseModel;

use function explode;
use function iterator_to_array;
use function str_contains;
use function trim;

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
        $nested = $this->getNestedFieldPath($field);

        if ($nested !== null) {
            [$nestedName, $nestedAttribute] = $nested;
            $nestedModel = $this->getValue($nestedName);

            if ($nestedModel instanceof FormModelInterface) {
                return $nestedModel->getFieldConfig($nestedAttribute);
            }
        }

        return $this->getFieldConfigs()[$field] ?? [];
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
        $nested = $this->getNestedFieldPath($field);

        if ($nested !== null) {
            [$nestedName, $nestedAttribute] = $nested;
            $nestedModel = $this->getValue($nestedName);

            if ($nestedModel instanceof FormModelInterface) {
                return $nestedModel->getHint($nestedAttribute);
            }
        }

        return $this->getHints()[$field] ?? '';
    }

    public function getHints(): array
    {
        return [];
    }

    public function getLabel(string $field): string
    {
        $nested = $this->getNestedFieldPath($field);

        if ($nested !== null) {
            [$nestedName, $nestedAttribute] = $nested;
            $nestedModel = $this->getValue($nestedName);

            if ($nestedModel instanceof FormModelInterface) {
                return $nestedModel->getLabel($nestedAttribute);
            }
        }

        $generateLabel = WordCaseConverter::toTitleWords($field);

        return $this->getLabels()[$field] ?? $generateLabel;
    }

    public function getLabels(): array
    {
        return [];
    }

    public function getPlaceholder(string $field): string
    {
        $nested = $this->getNestedFieldPath($field);

        if ($nested !== null) {
            [$nestedName, $nestedAttribute] = $nested;
            $nestedModel = $this->getValue($nestedName);

            if ($nestedModel instanceof FormModelInterface) {
                return $nestedModel->getPlaceholder($nestedAttribute);
            }
        }

        return $this->getPlaceholders()[$field] ?? '';
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
        $nested = $this->getNestedFieldPath($field);

        if ($nested !== null) {
            [$nestedName, $nestedAttribute] = $nested;
            $nestedModel = $this->getValue($nestedName);

            if ($nestedModel instanceof FormModelInterface) {
                return $nestedModel->getRule($nestedAttribute);
            }
        }

        $rules = $this->getRules();

        if ($rules instanceof Traversable) {
            $rules = iterator_to_array($rules);
        }

        $rule = $rules[$field] ?? null;

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
     * Splits a field path into parent and nested segments.
     *
     * @throws InvalidArgumentException If the nested field path format is invalid.
     *
     * @phpstan-return array{0: string, 1: string}|null
     */
    private function getNestedFieldPath(string $field): array|null
    {
        if (!str_contains($field, '.')) {
            return null;
        }

        $result = explode('.', $field, 2);
        $parentField = trim($result[0]);
        $nestedField = trim($result[1] ?? '');

        if ($parentField === '' || $nestedField === '') {
            throw new InvalidArgumentException(Message::INVALID_NESTED_FIELD_PATH->getMessage($field));
        }

        return [$parentField, $nestedField];
    }
}
