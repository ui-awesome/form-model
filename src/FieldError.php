<?php

declare(strict_types=1);

namespace UIAwesome\FormModel;

use function array_flip;
use function array_intersect_key;
use function reset;

/**
 * Stores and queries validation errors by field.
 *
 * Usage example:
 * ```php
 * $errors = new FieldError();
 * $errors->add('email', 'Email is invalid.');
 * $firstEmailError = $errors->getField('email', true);
 * ```
 *
 * @copyright Copyright (C) 2024 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class FieldError
{
    /**
     * Stores error messages indexed by field name.
     *
     * @phpstan-var array<string, array<int, string>>
     */
    private array $errors = [];

    /**
     * Adds an error message for a field.
     *
     * Usage example:
     * ```php
     * $errors->add('username', 'Username is required.');
     * ```
     *
     * @param string $field Field name.
     * @param string $error Error message to append.
     */
    public function add(string $field, string $error): void
    {
        $this->errors[$field][] = $error;
    }

    /**
     * Clears errors for one field or all fields.
     *
     * Usage example:
     * ```php
     * $errors->clear('email');
     * $errors->clear();
     * ```
     *
     * @param string|null $field Field name, or `null` to clear all fields.
     */
    public function clear(string|null $field = null): void
    {
        if ($field !== null) {
            $this->errors[$field] = [];

            return;
        }

        $this->errors = [];
    }

    /**
     * Returns validation errors indexed by field name.
     *
     * Usage example:
     * ```php
     * $allErrors = $errors->get();
     * $firstErrors = $errors->get(true);
     * ```
     *
     * @param bool $first Whether to return only the first error for each field.
     *
     * @return array Errors indexed by field name. Returns an empty array when no errors exist.
     *
     * @phpstan-return array<string, array<int, string>|string>
     */
    public function get(bool $first = false): array
    {
        if ($first) {
            return $this->getFirstFields();
        }

        return array_filter($this->errors, static fn(array $value): bool => $value !== []);
    }

    /**
     * Returns all errors indexed by field name.
     *
     * @phpstan-return array<string, array<int, string>>
     */
    public function getAll(): array
    {
        /** @phpstan-var array<string, array<int, string>> $errors */
        return $this->get();
    }

    /**
     * Returns the first error for each field.
     *
     * @phpstan-return array<string, string>
     */
    public function getAllFirst(): array
    {
        return $this->getFirstFields();
    }

    /**
     * Returns the first error for one field.
     */
    public function getFirstForField(string $field): string
    {
        return $this->getFirst($field);
    }

    /**
     * Returns all errors for one field.
     *
     * @phpstan-return array<int, string>
     */
    public function getForField(string $field): array
    {
        /** @phpstan-var array<int, string> $errors */
        return $this->getField($field);
    }

    /**
     * Returns errors for a single field.
     *
     * Usage example:
     * ```php
     * $emailErrors = $errors->getField('email');
     * $firstEmailError = $errors->getField('email', true);
     * ```
     *
     * @param string $field Field name.
     * @param bool $first Whether to return only the first error.
     *
     * @return array|string Error list for the field, or the first error when `$first` is `true`.
     *
     * @phpstan-return array<int, string>|string
     */
    public function getField(string $field, bool $first = false): array|string
    {
        if ($first) {
            return $this->getFirst($field);
        }

        return $this->errors[$field] ?? [];
    }

    /**
     * Returns a flattened error summary.
     *
     * Usage example:
     * ```php
     * $summary = $errors->getSummary(['email', 'username']);
     * ```
     *
     * @param array $onlyFields Fields to include. Uses all fields when the list is empty.
     * @param bool $first Whether to include only the first error for each field.
     *
     * @return array Flat list of error messages.
     *
     * @phpstan-param list<string> $onlyFields
     * @phpstan-return array<int|string, string>
     */
    public function getSummary(array $onlyFields = [], bool $first = false): array
    {
        if ($first) {
            return $this->getSummaryFirst($onlyFields);
        }

        $errors = $this->errors;

        if ($onlyFields !== []) {
            $onlyFieldsMap = array_flip($onlyFields);

            $errors = array_intersect_key($errors, $onlyFieldsMap);
        }

        return $this->renderSummary($errors);
    }

    /**
     * Checks whether errors exist.
     *
     * Usage example:
     * ```php
     * $hasAny = $errors->has();
     * $hasEmailErrors = $errors->has('email');
     * ```
     *
     * @param string|null $field Field name, or `null` to check all fields.
     *
     * @return bool `true` if errors exist, otherwise `false`.
     */
    public function has(string|null $field = null): bool
    {
        if ($field === null) {
            return $this->get() !== [];
        }

        return isset($this->errors[$field]) && $this->errors[$field] !== [];
    }

    /**
     * Checks whether a field has been validated successfully.
     *
     * Usage example:
     * ```php
     * $isValidated = $errors->isValidated('email');
     * ```
     *
     * @param string $field Field name.
     *
     * @return bool `true` if the field exists in the error map with no messages, otherwise `false`.
     */
    public function isValidated(string $field): bool
    {
        return isset($this->errors[$field]) && $this->errors[$field] === [];
    }

    /**
     * Replaces the full error collection.
     *
     * Usage example:
     * ```php
     * $errors->set(['email' => ['Email is invalid.']]);
     * ```
     *
     * @param array $values Error messages indexed by field name.
     *
     * @phpstan-param array<string, array<int, string>> $values
     */
    public function set(array $values): void
    {
        $this->errors = $values;
    }

    /**
     * Returns the first error for a field.
     *
     * @param string $field Field name.
     *
     * @return string First error message, or an empty string when no errors exist.
     */
    private function getFirst(string $field): string
    {
        if (!isset($this->errors[$field]) || $this->errors[$field] === []) {
            return '';
        }

        return reset($this->errors[$field]);
    }

    /**
     * Returns the first error for each field.
     *
     * @return array First error messages indexed by field name.
     *
     * @phpstan-return array<string, string>
     */
    private function getFirstFields(): array
    {
        $errors = [];

        /**
         * @phpstan-var string $name
         * @phpstan-var array<int, string> $es
         */
        foreach ($this->errors as $name => $es) {
            if ($es !== []) {
                $errors[$name] = reset($es);
            }
        }

        return $errors;
    }

    /**
     * Returns a summary containing the first error for each field.
     *
     * @phpstan-param list<string> $onlyFields
     * @phpstan-return array<int|string, string>
     */
    private function getSummaryFirst(array $onlyFields = []): array
    {
        $firstErrors = $this->getFirstFields();

        if ($onlyFields !== []) {
            $onlyFieldsMap = array_flip($onlyFields);

            $firstErrors = array_intersect_key($firstErrors, $onlyFieldsMap);
        }

        return $firstErrors;
    }

    /**
     * Flattens grouped errors into a summary list.
     *
     * @phpstan-param array<int, array<int|string, string>>|array<string, array<int, string>> $errors
     * @phpstan-return array<int|string, string>
     */
    private function renderSummary(array $errors): array
    {
        $lines = [];

        /** @phpstan-var array<int|string, string> $error */
        foreach ($errors as $error) {
            foreach ($error as $key => $line) {
                if (is_int($key)) {
                    $lines[] = $line;
                } else {
                    $lines[$key] = $line;
                }
            }
        }

        return $lines;
    }
}
