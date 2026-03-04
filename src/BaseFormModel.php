<?php

declare(strict_types=1);

namespace UIAwesome\FormModel;

use InvalidArgumentException;
use PHPForge\Helper\{Reflector, WordCaseConverter};
use Traversable;
use UIAwesome\FormModel\Attribute\{FieldConfig, Hint, Label, Placeholder};
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

    public function getErrors(): array
    {
        return $this->error()->getAll();
    }

    public function getErrorSummary(array $onlyFields = [], bool $first = false): array
    {
        return $this->error()->getSummary($onlyFields, $first);
    }

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

        $fieldConfig = $this->getFieldPropertyAttribute($field, FieldConfig::class);

        if ($fieldConfig instanceof FieldConfig) {
            return $fieldConfig->value;
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

        $hint = $this->getFieldPropertyAttribute($field, Hint::class);

        if ($hint instanceof Hint) {
            return $hint->value;
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

        $label = $this->getFieldPropertyAttribute($field, Label::class);

        if ($label instanceof Label) {
            return $label->value;
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

        $placeholder = $this->getFieldPropertyAttribute($field, Placeholder::class);

        if ($placeholder instanceof Placeholder) {
            return $placeholder->value;
        }

        return $this->getPlaceholders()[$field] ?? '';
    }

    public function getPlaceholders(): array
    {
        return [];
    }

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
     * Returns the first matching instantiated property attribute for a field.
     *
     * @param string $field Field name to check for the attribute.
     * @param string $attribute Attribute class name to look for.
     *
     * @return object|null Instantiated attribute object if found, or `null` if not found.
     */
    private function getFieldPropertyAttribute(string $field, string $attribute): object|null
    {
        if (!Reflector::hasProperty($this, $field)) {
            return null;
        }

        return Reflector::firstPropertyAttribute($this, $field, $attribute);
    }

    /**
     * Splits a field path into parent and nested segments.
     *
     * @throws InvalidArgumentException If the nested field path format is invalid.
     *
     * @return array|null An array containing the parent field and nested field, or `null` if the path is not nested.
     *
     * @phpstan-return array{string, string}|null
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
