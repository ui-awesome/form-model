<?php

declare(strict_types=1);

namespace UIAwesome\FormModel;

use function array_flip;
use function array_intersect_key;
use function reset;

/**
 * FieldError represents a collection of field errors.
 */
final class FieldError
{
    /**
     * @phpstan-var array<string, array<int, string>>
     */
    private array $errors = [];

    /**
     * Add an error for the specified property.
     *
     * @param string $property The property name.
     * @param string $error The error message to be added to the property.
     */
    public function add(string $property, string $error): void
    {
        $this->errors[$property][] = $error;
    }

    /**
     * Removes errors for all properties or a single property.
     *
     * @param string|null $property The property name or null to remove errors for all properties.
     * For default is `null`.
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
     * Get all errors for all properties.
     *
     * @param bool $first Whether to return only the first error of each property.
     *
     * @return array The errors for all properties as a two-dimensional array.
     * Empty array is returned if no error.
     * If `$first` is `true`, only the first error of each property is returned.
     *
     * ```php
     * [
     *     'username' => [
     *         'Username is required.',
     *         'Username must contain only word characters.',
     *     ],
     *     'email' => [
     *         'Email address is invalid.',
     *     ]
     * ]
     * ```
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
     * Get the error of every property in the collection.
     *
     * @param string $property The property name.
     * @param bool $first Whether to return only the first error of the specified property.
     *
     * @return array|string The errors for an property with a given name.
     * Empty array is returned if no error.
     * If `$first` is `true`, only the first error is returned.
     *
     * @phpstan-return array<string>|string
     */
    public function getProperty(string $property, bool $first = false): array|string
    {
        if ($first) {
            return $this->getFirst($property);
        }

        return $this->errors[$property] ?? [];
    }

    /**
     * Get all errors for all properties.
     *
     * @param array $onlyProperties List of properties to return errors.
     * @param bool $first Whether to return only the first error of each property.
     *
     * @return array The errors for all properties as a one-dimensional array. Empty array is returned if no error.
     *
     * @phpstan-param list<string> $onlyProperties
     * @phpstan-return array<int|string, string>
     */
    public function getSummary(array $onlyProperties = [], bool $first = false): array
    {
        if ($first) {
            return $this->getSummaryFirst();
        }

        $errors = $this->errors;

        if ($onlyProperties !== []) {
            $onlyPropertiesMap = array_flip($onlyProperties);

            $errors = array_intersect_key($errors, $onlyPropertiesMap);
        }

        return $this->renderSummary($errors);
    }

    /**
     * Returns a value indicating whether there is any validation error.
     *
     * @param string|null $property The property name. Use null to check all properties.
     *
     * @return bool Whether there is any error.
     */
    public function has(string|null $property = null): bool
    {
        if ($property === null) {
            return $this->get() !== [];
        }

        return isset($this->errors[$property]) && $this->errors[$property] !== [];
    }

    /**
     * Returns a `true` indicating whether the property is validated successfully, `false` otherwise.
     *
     * @param string $property The property name.
     */
    public function hasValidate(string $property): bool
    {
        return isset($this->errors[$property]) && $this->errors[$property] === [];
    }

    /**
     * Set errors for multiple properties.
     *
     * @param array $values The property names and the corresponding error messages.
     *
     * @phpstan-param array<string, array<int, string>> $values
     */
    public function set(array $values): void
    {
        $this->errors = $values;
    }

    /**
     * Get the first error of the specified property.
     *
     * @param string $property The property name.
     *
     * @return string The error message. Empty string is returned if there is no error.
     */
    private function getFirst(string $property): string
    {
        if (!isset($this->errors[$property]) || $this->errors[$property] === []) {
            return '';
        }

        return reset($this->errors[$property]);
    }

    /**
     * Get the first errors.
     *
     * @return array The first errors. The array keys are the attribute names, and the array values are the
     * corresponding error messages. An empty array will be returned if there is no error.
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
     * Get the first error of every property.
     *
     * @return array The first error of every property in the collection.
     * Empty array is returned if no error.
     *
     * @phpstan-return array<int|string, string>
     */
    private function getSummaryFirst(): array
    {
        return $this->renderSummary([$this->getFirsts()]);
    }

    /**
     * Render the summary of all errors.
     *
     * @return array The errors for all properties as a two-dimensional array.
     * Empty array is returned if no error.
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
