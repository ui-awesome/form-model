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
     * @psalm-var string[][]
     */
    private array $errors = [];

    public function __construct() {}

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
    public function clear(string $property = null): void
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
     * @param array $onlyProperties List of properties to return errors.
     * @param bool $first Whether to return only the first error of each property.
     *
     * @return array The errors for all properties as a one-dimensional array. Empty array is returned if no error.
     */
    public function getSummary(array $onlyProperties = [], bool $first = false): array
    {
        if ($first === true) {
            return $this->getSummaryFirst();
        }

        $errors = $this->errors;

        if ($onlyProperties !== []) {
            $errors = array_intersect_key($errors, array_flip($onlyProperties));
        }

        return $this->renderSummary($errors);
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
     */
    public function get(bool $first = false): array
    {
        if ($first === true) {
            return $this->getFirsts();
        }

        return array_filter(
            $this->errors,
            static function ($value) {
                return $value !== [];
            }
        );
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
     * @psalm-return array<string>|string
     */
    public function getProperty(string $property, bool $first = false): array|string
    {
        if ($first === true) {
            return $this->getFirst($property);
        }

        return $this->errors[$property] ?? [];
    }

    /**
     * Returns a value indicating whether there is any validation error.
     *
     * @param string|null $property The property name. Use null to check all properties.
     *
     * @return bool Whether there is any error.
     */
    public function has(string $property = null): bool
    {
        if ($property === null) {
            return $this->get() !== [];
        }

        return !empty($this->errors[$property]);
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
     * @psalm-param array<array<string>> $values
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
        if (empty($this->errors[$property])) {
            return '';
        }

        return reset($this->errors[$property]);
    }

    /**
     * Get the first errors.
     *
     * @return array The first errors. The array keys are the attribute names, and the array values are the
     * corresponding error messages. An empty array will be returned if there is no error.
     */
    private function getFirsts(): array
    {
        if ($this->errors === []) {
            return [];
        }

        $errors = [];

        /**
         * @psalm-var string $name
         * @psalm-var array<string> $es
         */
        foreach ($this->errors as $name => $es) {
            if (!empty($es)) {
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
     */
    private function renderSummary(array $errors): array
    {
        $lines = [];

        /** @psalm-var array $error */
        foreach ($errors as $error) {
            $lines = [...$lines, ...$error];
        }

        return $lines;
    }
}
