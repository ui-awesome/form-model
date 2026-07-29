<?php

declare(strict_types=1);

namespace UIAwesome\FormModel;

use UIAwesome\Model\ModelInterface;

/**
 * Defines the contract for form models with validation metadata and field errors.
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
 */
interface FormModelInterface extends ModelInterface
{
    /**
     * Adds an error for a field.
     *
     * Usage example:
     * ```php
     * $form->addError('email', 'Email is invalid.');
     * ```
     *
     * @param string $field Field name.
     * @param string $error Error message to append.
     */
    public function addError(string $field, string $error): void;

    /**
     * Clears errors for one field or all fields.
     *
     * Usage example:
     * ```php
     * $form->clearError('email');
     * $form->clearError();
     * ```
     *
     * @param string|null $field Field name, or `null` to clear all fields.
     */
    public function clearError(string|null $field = null): void;

    /**
     * Returns errors for one field.
     *
     * Usage example:
     * ```php
     * $errors = $form->getError('email');
     * ```
     *
     * @param string $field Field name.
     *
     * @return array<int, string> Error list for the field.
     */
    public function getError(string $field): array;

    /**
     * Returns validation errors indexed by field name.
     *
     * Usage example:
     * ```php
     * $allErrors = $form->getErrors();
     * ```
     *
     * @return array<string, array<int, string>> Errors indexed by field name. Returns an empty array when no errors
     * exist.
     */
    public function getErrors(): array;

    /**
     * Returns a flattened error summary.
     *
     * Usage example:
     * ```php
     * $summary = $form->getErrorSummary(['email', 'username']);
     * ```
     *
     * @param list<string> $onlyFields Fields to include. Uses all fields when the list is empty.
     * @param bool $first Whether to return only the first error of each field.
     *
     * @return array<int|string, string> Flat list of error messages when `$first` is `false`; field-indexed first
     * errors when `$first` is `true`.
     */
    public function getErrorSummary(array $onlyFields = [], bool $first = false): array;

    /**
     * Returns the field configuration for one field.
     *
     * A field configuration maps a method name to the argument, or list of arguments, applied to the field or to the
     * widget that renders it.
     *
     * Usage example:
     * ```php
     * $emailFieldConfig = $form->getFieldConfig('email');
     * // ['class' => ['w-full rounded-md']]
     * ```
     *
     * @param string $field Field name.
     *
     * @return array<string, mixed> Field configuration for the field. Returns an empty array when the field has no
     * configuration.
     */
    public function getFieldConfig(string $field): array;

    /**
     * Returns field configuration arrays indexed by field name.
     *
     * Each configuration maps a method name to the argument, or list of arguments, applied to the field or to the
     * widget that renders it.
     *
     * Usage example:
     * ```php
     * $fieldConfig = $form->getFieldConfigs();
     * // ['email' => ['class' => ['w-full rounded-md']]]
     * ```
     *
     * @return array<string, array<string, mixed>> Field configuration arrays indexed by field name.
     */
    public function getFieldConfigs(): array;

    /**
     * Returns the first error for one field.
     *
     * Usage example:
     * ```php
     * $firstError = $form->getFirstError('email');
     * ```
     *
     * @param string $field Field name.
     *
     * @return string First error for the field, or an empty string when no errors exist.
     */
    public function getFirstError(string $field): string;

    /**
     * Returns the first validation error indexed by field name.
     *
     * Usage example:
     * ```php
     * $firstErrors = $form->getFirstErrors();
     * ```
     *
     * @return array<string, string> First errors indexed by field name. Returns an empty array when no errors exist.
     */
    public function getFirstErrors(): array;

    /**
     * Returns the hint text for one field.
     *
     * Usage example:
     * ```php
     * $hint = $form->getHint('email');
     * ```
     *
     * @param string $field Field name.
     *
     * @return string Hint text for the field.
     */
    public function getHint(string $field): string;

    /**
     * Returns hint text indexed by field name.
     *
     * Usage example:
     * ```php
     * $hints = $form->getHints();
     * ```
     *
     * @return array<string, string> Hint text indexed by field name.
     */
    public function getHints(): array;

    /**
     * Returns the label text for one field.
     *
     * Usage example:
     * ```php
     * $label = $form->getLabel('email');
     * ```
     *
     * @param string $field Field name.
     *
     * @return string Label text for the field.
     */
    public function getLabel(string $field): string;

    /**
     * Returns label text indexed by field name.
     *
     * Usage example:
     * ```php
     * $labels = $form->getLabels();
     * ```
     *
     * @return array<string, string> Label text indexed by field name.
     */
    public function getLabels(): array;

    /**
     * Returns the placeholder text for one field.
     *
     * Usage example:
     * ```php
     * $placeholder = $form->getPlaceholder('email');
     * ```
     *
     * @param string $field Field name.
     *
     * @return string Placeholder text for the field.
     */
    public function getPlaceholder(string $field): string;

    /**
     * Returns placeholder text indexed by field name.
     *
     * Usage example:
     * ```php
     * $placeholders = $form->getPlaceholders();
     * ```
     *
     * @return array<string, string> Placeholder text indexed by field name.
     */
    public function getPlaceholders(): array;

    /**
     * Returns validation rules for one field.
     *
     * Usage example:
     * ```php
     * $emailRules = $form->getRule('email');
     * ```
     *
     * @param string $field Field name.
     *
     * @return array<mixed, mixed>|null Validation rules for the field, or `null` when no rules are defined.
     */
    public function getRule(string $field): array|null;

    /**
     * Returns validation rules indexed by field name.
     *
     * Usage example:
     * ```php
     * $rules = $form->getRules();
     * ```
     *
     * @return iterable<string, array<mixed, mixed>> Validation rules indexed by field name.
     */
    public function getRules(): iterable;

    /**
     * Checks whether errors exist.
     *
     * Usage example:
     * ```php
     * $hasAnyError = $form->hasError();
     * $hasEmailError = $form->hasError('email');
     * ```
     *
     * @param string|null $field Field name, or `null` to check all fields.
     *
     * @return bool `true` if errors exist, otherwise `false`.
     */
    public function hasError(string|null $field = null): bool;

    /**
     * Checks whether a field has been validated successfully.
     *
     * Usage example:
     * ```php
     * $isValid = $form->isValidated('email');
     * ```
     *
     * @param string $field Field name.
     *
     * @return bool `true` if the field exists in the error map with no messages, otherwise `false`.
     */
    public function isValidated(string $field): bool;

    /**
     * Replaces all field errors.
     *
     * Usage example:
     * ```php
     * $form->setErrors(['email' => ['Email is invalid.']]);
     * ```
     *
     * @param array<string, array<int, string>> $values Error messages indexed by field name.
     */
    public function setErrors(array $values): void;
}
