<?php

declare(strict_types=1);

namespace UIAwesome\FormModel;

use UIAwesome\Model\ModelInterface;

/**
 * Defines the contract for form models with validation metadata and field errors.
 *
 * Usage example:
 * ```php
 * final class UserForm extends AbstractFormModel
 * {
 *     public string $email = '';
 * }
 *
 * $form = new UserForm();
 * $form->addPropertyError('email', 'Email is invalid.');
 * ```
 *
 * @copyright Copyright (C) 2024 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
interface FormModelInterface extends ModelInterface
{
    /**
     * Adds an error for a property.
     *
     * Usage example:
     * ```php
     * $form->addPropertyError('email', 'Email is invalid.');
     * ```
     *
     * @param string $property Property name.
     * @param string $error Error message to append.
     */
    public function addPropertyError(string $property, string $error): void;

    /**
     * Clears errors for one property or all properties.
     *
     * Usage example:
     * ```php
     * $form->clearError('email');
     * $form->clearError();
     * ```
     *
     * @param string|null $property Property name, or `null` to clear all properties.
     */
    public function clearError(string|null $property = null): void;

    /**
     * Returns validation errors indexed by property name.
     *
     * Usage example:
     * ```php
     * $allErrors = $form->getErrors();
     * $firstErrors = $form->getErrors(true);
     * ```
     *
     * @param bool $first Whether to return only the first error of each property.
     *
     * @return array Errors indexed by property name. Returns an empty array when no errors exist.
     *
     * @phpstan-return array<string, array<int, string>|string>
     */
    public function getErrors(bool $first = false): array;

    /**
     * Returns a flattened error summary.
     *
     * Usage example:
     * ```php
     * $summary = $form->getErrorSummary(['email', 'username']);
     * ```
     *
     * @param array $onlyProperties Properties to include. Uses all properties when the list is empty.
     * @param bool $first Whether to return only the first error of each property.
     *
     * @return array Flat list of error messages.
     *
     * @phpstan-param list<string> $onlyProperties
     * @phpstan-return array<int|string, string>
     */
    public function getErrorSummary(array $onlyProperties = [], bool $first = false): array;

    /**
     * Returns field configuration arrays indexed by property name.
     *
     * Usage example:
     * ```php
     * $fieldConfig = $form->getFieldConfigByProperties();
     * ```
     *
     * @return array Field configuration arrays indexed by property name.
     *
     * @phpstan-return array<string, array<string, array<int, string>>>
     */
    public function getFieldConfigByProperties(): array;

    /**
     * Returns the field configuration for one property.
     *
     * Usage example:
     * ```php
     * $emailFieldConfig = $form->getFieldConfigByProperty('email');
     * ```
     *
     * @param string $property Property name.
     *
     * @return array Field configuration for the property.
     *
     * @phpstan-return array<int|string, mixed>
     */
    public function getFieldConfigByProperty(string $property): array;

    /**
     * Returns the hint text for one property.
     *
     * Usage example:
     * ```php
     * $hint = $form->getHintByProperty('email');
     * ```
     *
     * @param string $property Property name.
     *
     * @return string Hint text for the property.
     */
    public function getHintByProperty(string $property): string;

    /**
     * Returns hint text indexed by property name.
     *
     * Usage example:
     * ```php
     * $hints = $form->getHints();
     * ```
     *
     * @phpstan-return string[]
     */
    public function getHints(): array;

    /**
     * Returns the label text for one property.
     *
     * Usage example:
     * ```php
     * $label = $form->getLabelByProperty('email');
     * ```
     *
     * @param string $property Property name.
     *
     * @return string Label text for the property.
     */
    public function getLabelByProperty(string $property): string;

    /**
     * Returns label text indexed by property name.
     *
     * Usage example:
     * ```php
     * $labels = $form->getLabels();
     * ```
     *
     * @phpstan-return string[]
     */
    public function getLabels(): array;

    /**
     * Returns the placeholder text for one property.
     *
     * Usage example:
     * ```php
     * $placeholder = $form->getPlaceholderByProperty('email');
     * ```
     *
     * @param string $property Property name.
     *
     * @return string Placeholder text for the property.
     */
    public function getPlaceholderByProperty(string $property): string;

    /**
     * Returns placeholder text indexed by property name.
     *
     * Usage example:
     * ```php
     * $placeholders = $form->getPlaceholders();
     * ```
     *
     * @phpstan-return string[]
     */
    public function getPlaceholders(): array;

    /**
     * Returns errors for one property.
     *
     * Usage example:
     * ```php
     * $errors = $form->getPropertyError('email');
     * $firstError = $form->getPropertyError('email', true);
     * ```
     *
     * @param string $property Property name.
     * @param bool $first Whether to return only the first error of the specified property.
     *
     * @return array|string Error list for the property, or the first error when `$first` is `true`.
     *
     * @phpstan-return array<int, string>|string
     */
    public function getPropertyError(string $property, bool $first = false): array|string;

    /**
     * Returns validation rules indexed by property name.
     *
     * Usage example:
     * ```php
     * $rules = $form->getRules();
     * ```
     *
     * @return iterable Validation rules indexed by property name.
     *
     * @phpstan-return iterable<string, array<mixed, mixed>>
     */
    public function getRules(): iterable;

    /**
     * Returns validation rules for one property.
     *
     * Usage example:
     * ```php
     * $emailRules = $form->getRulesByProperty('email');
     * ```
     *
     * @param string $property Property name.
     *
     * @return array|null Validation rules for the property, or `null` when no rules are defined.
     *
     * @phpstan-return array<mixed, mixed>|null
     */
    public function getRulesByProperty(string $property): array|null;

    /**
     * Checks whether errors exist.
     *
     * Usage example:
     * ```php
     * $hasAnyError = $form->hasPropertyError();
     * $hasEmailError = $form->hasPropertyError('email');
     * ```
     *
     * @param string|null $property Property name, or `null` to check all properties.
     *
     * @return bool `true` if errors exist, otherwise `false`.
     */
    public function hasPropertyError(string|null $property = null): bool;

    /**
     * Checks whether a property has been validated successfully.
     *
     * Usage example:
     * ```php
     * $isValid = $form->hasPropertyValidate('email');
     * ```
     *
     * @param string $property Property name.
     *
     * @return bool `true` if the property exists in the error map with no messages, otherwise `false`.
     */
    public function hasPropertyValidate(string $property): bool;

    /**
     * Replaces all property errors.
     *
     * Usage example:
     * ```php
     * $form->setErrors(['email' => ['Email is invalid.']]);
     * ```
     *
     * @param array $values Error messages indexed by property name.
     *
     * @phpstan-param array<string, array<int, string>> $values
     */
    public function setErrors(array $values): void;
}
