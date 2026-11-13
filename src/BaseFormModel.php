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
 * use UIAwesome\FormModel\Attribute\{FieldConfig, Hint, Label, Placeholder};
 *
 * final class UserForm extends BaseFormModel
 * {
 *     #[Hint('Use your account email address.')]
 *     #[Label('Email address')]
 *     #[Placeholder('name@example.com')]
 *     #[FieldConfig(['class' => ['w-full rounded-md border border-slate-300 px-3 py-2']])]
 *     public string $email = '';
 * }
 *
 * $form = new UserForm();
 *
 * $label = $form->getLabel('email');
 * // 'Email address'
 * ```
 */
abstract class BaseFormModel extends BaseModel implements FormModelInterface
{
    /**
     * Lazily initialized field-error storage.
     */
    private FieldError|null $fieldError = null;

    /**
     * Adds an error for a field.
     *
     * @param string $field Field name.
     * @param string $error Error message to append.
     */
    public function addError(string $field, string $error): void
    {
        $this->writableError()->add($field, $error);
    }

    /**
     * Clears errors for one field or all fields.
     *
     * @param string|null $field Field name, or `null` to clear all fields.
     */
    public function clearError(string|null $field = null): void
    {
        $this->writableError()->clear($field);
    }

    /**
     * Returns errors for one field.
     *
     * @param string $field Field name.
     *
     * @return array<int, string> Error list for the field.
     */
    public function getError(string $field): array
    {
        return $this->error()->getForField($field);
    }

    /**
     * Returns validation errors indexed by field name.
     *
     * @return array<string, array<int, string>> Errors indexed by field name. Returns an empty array when no errors
     * exist.
     */
    public function getErrors(): array
    {
        return $this->error()->getAll();
    }

    /**
     * Returns a flattened error summary.
     *
     * @param list<string> $onlyFields Fields to include. Uses all fields when the list is empty.
     * @param bool $first Whether to return only the first error of each field.
     *
     * @return array<int|string, string> Flat list of error messages when `$first` is `false`; field-indexed first
     * errors when `$first` is `true`.
     */
    public function getErrorSummary(array $onlyFields = [], bool $first = false): array
    {
        return $this->error()->getSummary($onlyFields, $first);
    }

    /**
     * Returns the field configuration for one field.
     *
     * A field configuration maps a method name to the argument, or list of arguments, applied to the field or to the
     * widget that renders it.
     *
     * @param string $field Field name.
     *
     * @return array<string, mixed> Field configuration for the field. Returns an empty array when the field has no
     * configuration.
     */
    public function getFieldConfig(string $field): array
    {
        $nested = $this->resolveNested($field);

        if ($nested !== null) {
            [$nestedModel, $nestedField] = $nested;

            return $nestedModel->getFieldConfig($nestedField);
        }

        $fieldConfig = $this->getFieldPropertyAttribute($field, FieldConfig::class);

        if ($fieldConfig instanceof FieldConfig) {
            return $fieldConfig->value;
        }

        return $this->getFieldConfigs()[$field] ?? [];
    }

    /**
     * Returns field configuration arrays indexed by field name.
     *
     * Each configuration maps a method name to the argument, or list of arguments, applied to the field or to the
     * widget that renders it.
     *
     * @return array<string, array<string, mixed>> Field configuration arrays indexed by field name.
     */
    public function getFieldConfigs(): array
    {
        return [];
    }

    /**
     * Returns the first error for one field.
     *
     * @param string $field Field name.
     *
     * @return string First error for the field, or an empty string when no errors exist.
     */
    public function getFirstError(string $field): string
    {
        return $this->error()->getFirstForField($field);
    }

    /**
     * Returns the first validation error indexed by field name.
     *
     * @return array<string, string> First errors indexed by field name. Returns an empty array when no errors exist.
     */
    public function getFirstErrors(): array
    {
        return $this->error()->getAllFirst();
    }

    /**
     * Returns the hint text for one field.
     *
     * @param string $field Field name.
     *
     * @return string Hint text for the field.
     */
    public function getHint(string $field): string
    {
        $nested = $this->resolveNested($field);

        if ($nested !== null) {
            [$nestedModel, $nestedField] = $nested;

            return $nestedModel->getHint($nestedField);
        }

        $hint = $this->getFieldPropertyAttribute($field, Hint::class);

        if ($hint instanceof Hint) {
            return $hint->value;
        }

        return $this->getHints()[$field] ?? '';
    }

    /**
     * Returns hint text indexed by field name.
     *
     * @return array<string, string> Hint text indexed by field name.
     */
    public function getHints(): array
    {
        return [];
    }

    /**
     * Returns the label text for one field.
     *
     * @param string $field Field name.
     *
     * @return string Label text for the field.
     */
    public function getLabel(string $field): string
    {
        $nested = $this->resolveNested($field);

        if ($nested !== null) {
            [$nestedModel, $nestedField] = $nested;

            return $nestedModel->getLabel($nestedField);
        }

        $label = $this->getFieldPropertyAttribute($field, Label::class);

        if ($label instanceof Label) {
            return $label->value;
        }

        $generateLabel = WordCaseConverter::toTitleWords($field);

        return $this->getLabels()[$field] ?? $generateLabel;
    }

    /**
     * Returns label text indexed by field name.
     *
     * @return array<string, string> Label text indexed by field name.
     */
    public function getLabels(): array
    {
        return [];
    }

    /**
     * Returns the placeholder text for one field.
     *
     * @param string $field Field name.
     *
     * @return string Placeholder text for the field.
     */
    public function getPlaceholder(string $field): string
    {
        $nested = $this->resolveNested($field);

        if ($nested !== null) {
            [$nestedModel, $nestedField] = $nested;

            return $nestedModel->getPlaceholder($nestedField);
        }

        $placeholder = $this->getFieldPropertyAttribute($field, Placeholder::class);

        if ($placeholder instanceof Placeholder) {
            return $placeholder->value;
        }

        return $this->getPlaceholders()[$field] ?? '';
    }

    /**
     * Returns placeholder text indexed by field name.
     *
     * @return array<string, string> Placeholder text indexed by field name.
     */
    public function getPlaceholders(): array
    {
        return [];
    }

    /**
     * Returns validation rules for one field.
     *
     * @param string $field Field name.
     *
     * @return array<mixed, mixed>|null Validation rules for the field, or `null` when no rules are defined.
     */
    public function getRule(string $field): array|null
    {
        $nested = $this->resolveNested($field);

        if ($nested !== null) {
            [$nestedModel, $nestedField] = $nested;

            return $nestedModel->getRule($nestedField);
        }

        $rules = $this->getRules();

        if ($rules instanceof Traversable) {
            $rules = iterator_to_array($rules);
        }

        $rule = $rules[$field] ?? null;

        return is_array($rule) ? $rule : null;
    }

    /**
     * Returns validation rules indexed by field name.
     *
     * @return iterable<string, array<mixed, mixed>> Validation rules indexed by field name.
     */
    public function getRules(): iterable
    {
        return [];
    }

    /**
     * Checks whether errors exist.
     *
     * @param string|null $field Field name, or `null` to check all fields.
     *
     * @return bool `true` if errors exist, otherwise `false`.
     */
    public function hasError(string|null $field = null): bool
    {
        return $this->error()->has($field);
    }

    /**
     * Checks whether a field has been validated successfully.
     *
     * @param string $field Field name.
     *
     * @return bool `true` if the field exists in the error map with no messages, otherwise `false`.
     */
    public function isValidated(string $field): bool
    {
        return $this->error()->isValidated($field);
    }

    /**
     * Replaces all field errors.
     *
     * @param array<string, array<int, string>> $values Error messages indexed by field name.
     */
    public function setErrors(array $values): void
    {
        $this->writableError()->set($values);
    }

    /**
     * Returns field error storage for reading, initializing it on first use.
     *
     * @return FieldError Field error storage instance.
     */
    private function error(): FieldError
    {
        return $this->fieldError ??= new FieldError();
    }

    /**
     * Returns the first matching instantiated property attribute for a field.
     *
     * @template TAttribute of object
     *
     * @param string $field Field name to check for the attribute.
     * @param class-string<TAttribute> $attribute Attribute class name to look for.
     *
     * @return TAttribute|null Instantiated attribute object if found, or `null` if not found.
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
     * @return array{string, string}|null An array containing the parent field and nested field, or `null` if the path
     * is not nested.
     */
    private function getNestedFieldPath(string $field): array|null
    {
        if (!str_contains($field, '.')) {
            return null;
        }

        $result = explode('.', $field, 2);
        $parentField = trim($result[0]);
        $nestedField = trim($result[1]);

        if ($parentField === '' || $nestedField === '') {
            throw new InvalidArgumentException(Message::INVALID_NESTED_FIELD_PATH->getMessage($field));
        }

        return [$parentField, $nestedField];
    }

    /**
     * Resolves a dot-notation field path to the nested form model that owns the field.
     *
     * Returns `null` when the path is not nested or when the parent value is not a form model, so callers fall back to
     * local metadata resolution using the original field name.
     *
     * @param string $field Field name, optionally in dot notation.
     *
     * @throws InvalidArgumentException If the nested field path format is invalid.
     *
     * @return array|null Nested form model and the field name to resolve on it, or `null` when the path resolves
     * locally.
     *
     * @return array{FormModelInterface, string}|null
     */
    private function resolveNested(string $field): array|null
    {
        $nested = $this->getNestedFieldPath($field);

        if ($nested !== null) {
            [$parentField, $nestedField] = $nested;
            $nestedModel = $this->getValue($parentField);

            if ($nestedModel instanceof FormModelInterface) {
                return [$nestedModel, $nestedField];
            }
        }

        return null;
    }

    /**
     * Returns field error storage for writing, initializing it or copying the current storage.
     *
     * Copying before every write keeps cloned models independent, including subclasses with their own clone hooks.
     *
     * @return FieldError Field error storage instance owned by this model.
     */
    private function writableError(): FieldError
    {
        return $this->fieldError = $this->fieldError === null ? new FieldError() : clone $this->fieldError;
    }
}
