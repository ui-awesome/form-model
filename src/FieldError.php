<?php

declare(strict_types=1);

namespace UIAwesome\FormModel;

use function array_flip;
use function array_intersect_key;
use function reset;

/**
 * Stores and queries validation errors by property.
 *
 * Usage example:
 * ```php
 * $errors = new FieldError();
 * $errors->add('email', 'Email is invalid.');
 * $firstEmailError = $errors->getProperty('email', true);
 * ```
 *
 * @copyright Copyright (C) 2024 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class FieldError
{
    /**
     * Stores error messages indexed by property name.
     *
     * @phpstan-var array<string, array<int, string>>
     */
    private array $errors = [];

    /**
     * Adds an error message for a property.
     *
     * Usage example:
     * ```php
     * $errors->add('username', 'Username is required.');
     * ```
     *
     * @param string $property Property name.
     * @param string $error Error message to append.
     */
    public function add(string $property, string $error): void
    {
        $this->errors[$property][] = $error;
    }

    /**
     * Clears errors for one property or all properties.
     *
     * Usage example:
     * ```php
     * $errors->clear('email');
     * $errors->clear();
     * ```
     *
     * @param string|null $property Property name, or `null` to clear all properties.
     */
    public function clear(string|null $property = null): void
    {
        if ($property !== null) {
            $this->errors[$property] = [];

            return;
        }

        $this->errors = [];
    }

    /**
     * Returns validation errors indexed by property name.
     *
     * Usage example:
     * ```php
     * $allErrors = $errors->get();
     * $firstErrors = $errors->get(true);
     * ```
     *
     * @param bool $first Whether to return only the first error for each property.
     *
     * @return array Errors indexed by property name. Returns an empty array when no errors exist.
     *
     * @phpstan-return array<string, array<int, string>|string>
     */
    public function get(bool $first = false): array
    {
        if ($first) {
            return $this->getFirsts();
        }

        return array_filter($this->errors, static fn(array $value): bool => $value !== []);
    }

    /**
     * Returns errors for a single property.
     *
     * Usage example:
     * ```php
     * $emailErrors = $errors->getProperty('email');
     * $firstEmailError = $errors->getProperty('email', true);
     * ```
     *
     * @param string $property Property name.
     * @param bool $first Whether to return only the first error.
     *
     * @return array|string Error list for the property, or the first error when `$first` is `true`.
     *
     * @phpstan-return array<int, string>|string
     */
    public function getProperty(string $property, bool $first = false): array|string
    {
        if ($first) {
            return $this->getFirst($property);
        }

        return $this->errors[$property] ?? [];
    }

    /**
     * Returns a flattened error summary.
     *
     * Usage example:
     * ```php
     * $summary = $errors->getSummary(['email', 'username']);
     * ```
     *
     * @param array $onlyProperties Properties to include. Uses all properties when the list is empty.
     * @param bool $first Whether to include only the first error for each property.
     *
     * @return array Flat list of error messages.
     *
     * @phpstan-param list<string> $onlyProperties
     * @phpstan-return array<int|string, string>
     */
    public function getSummary(array $onlyProperties = [], bool $first = false): array
    {
        if ($first) {
            return $this->getSummaryFirst($onlyProperties);
        }

        $errors = $this->errors;

        if ($onlyProperties !== []) {
            $onlyPropertiesMap = array_flip($onlyProperties);

            $errors = array_intersect_key($errors, $onlyPropertiesMap);
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
     * @param string|null $property Property name, or `null` to check all properties.
     *
     * @return bool `true` if errors exist, otherwise `false`.
     */
    public function has(string|null $property = null): bool
    {
        if ($property === null) {
            return $this->get() !== [];
        }

        return isset($this->errors[$property]) && $this->errors[$property] !== [];
    }

    /**
     * Checks whether a property has been validated successfully.
     *
     * Usage example:
     * ```php
     * $isValidated = $errors->hasValidate('email');
     * ```
     *
     * @param string $property Property name.
     *
     * @return bool `true` if the property exists in the error map with no messages, otherwise `false`.
     */
    public function hasValidate(string $property): bool
    {
        return isset($this->errors[$property]) && $this->errors[$property] === [];
    }

    /**
     * Replaces the full error collection.
     *
     * Usage example:
     * ```php
     * $errors->set(['email' => ['Email is invalid.']]);
     * ```
     *
     * @param array $values Error messages indexed by property name.
     *
     * @phpstan-param array<string, array<int, string>> $values
     */
    public function set(array $values): void
    {
        $this->errors = $values;
    }

    /**
     * Returns the first error for a property.
     *
     * @param string $property Property name.
     *
     * @return string First error message, or an empty string when no errors exist.
     */
    private function getFirst(string $property): string
    {
        if (!isset($this->errors[$property]) || $this->errors[$property] === []) {
            return '';
        }

        return reset($this->errors[$property]);
    }

    /**
     * Returns the first error for each property.
     *
     * @return array First error messages indexed by property name.
     *
     * @phpstan-return array<string, string>
     */
    private function getFirsts(): array
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
     * Returns a summary containing the first error for each property.
     *
     * @phpstan-param list<string> $onlyProperties
     * @phpstan-return array<int|string, string>
     */
    private function getSummaryFirst(array $onlyProperties = []): array
    {
        $firstErrors = $this->getFirsts();

        if ($onlyProperties !== []) {
            $onlyPropertiesMap = array_flip($onlyProperties);

            $firstErrors = array_intersect_key($firstErrors, $onlyPropertiesMap);
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
