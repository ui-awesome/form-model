<?php

declare(strict_types=1);

namespace UIAwesome\FormModel;

use UIAwesome\{Html\Interop\InputInterface, Model\ModelInterface};

/**
 * The FormModelInterface class defines a set of methods that must be implemented by classes that represent a form
 * model.
 *
 * A form model is a class that represents a form in a web application and is used to validate user input and handle
 * form submissions. It is usually used in conjunction with a view template that renders the form, and a controller that
 * processes the form submission and performs any necessary business logic.
 */
interface FormModelInterface extends ModelInterface
{
    /**
     * Add an error for the specified property.
     *
     * @param string $property The property name.
     * @param string $error The error message to be added to the property.
     */
    public function addPropertyError(string $property, string $error): void;

    /**
     * Set html validation rules for the specified property.
     *
     * @param InputInterface $input The input widget.
     * @param string $property The property name.
     *
     * @return InputInterface The input widget with the validation rules applied.
     */
    public function applyToHtmlRulesByProperty(InputInterface $input, string $property): InputInterface;

    /**
     * Clear errors for all or a single property.
     *
     * @param string|null $property The property name. Use null to clear errors for all properties.
     */
    public function clearError(string $property = null): void;

    /**
     * Get the error of every property in the collection.
     *
     * @param array $onlyProperties List of properties to return errors.
     * @param bool $first Whether to return only the first error of each property.
     *
     * @return array The errors for all properties as a one-dimensional array.
     * Empty array is returned if no error.
     * If `$first` is `true`, only the first error of each property is returned.
     */
    public function getErrorSummary(array $onlyProperties = [], bool $first = false): array;

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
    public function getErrors(bool $first = false): array;

    /**
     * @param string $property The property name.
     *
     * @return string The text hint for the specified property.
     */
    public function getHintByProperty(string $property): string;

    /**
     * @return array Allows defining hints for the form model propierties in associative array format.
     *
     * ```php
     * [
     *     'property' => 'hintText',
     * ]
     * ```
     *
     * @psalm-return string[]
     */
    public function getHints(): array;

    /**
     * @param string $property The property name.
     *
     * @return string the text label for the specified property.
     */
    public function getLabelByProperty(string $property): string;

    /**
     * @return array Allows defining labels for the form model properties in associative array format.
     *
     * ```php
     * [
     *     'property' => 'labelText',
     * ]
     *
     * @psalm-return string[]
     */
    public function getLabels(): array;

    /**
     * @param string $property The property name.
     *
     * @return string The text placeholder for the specified property.
     */
    public function getPlaceholderByProperty(string $property): string;

    /**
     * @return array Allows defining placeholders for the form model properties in associative array format.
     *
     * ```php
     * [
     *     'property' => 'placeholderText',
     * ]
     * ```
     *
     * @psalm-return string[]
     */
    public function getPlaceholders(): array;

    /**
     * Get the errors for a single property.
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
    public function getPropertyError(string $property, bool $first = false): array|string;

    /**
     * @return iterable A set of validation rules.
     */
    public function getRules(): iterable;

    /**
     * @param string $property The property name.
     *
     * @return array|null The validation rules for the specified property. Null is returned if no rules are defined.
     */
    public function getRulesByProperty(string $property): array|null;

    /**
     * Returns the configurations for multiple widgets, indexed by Widget class name.
     *
     * @return array The configurations for multiple widgets in an associative array format.
     *
     * Example:
     * ```php
     * [
     *     Button::class => [
     *         'class()' => ['text-gray-100 dark:text-gray-100'],
     *     ],
     * ]
     * ```
     */
    public function getWidgetConfig(): array;

    /**
     * Returns the widget configuration for the specified widget class.
     *
     * @param string $class The widget class name.
     *
     * @return array The widget configuration for the specified widget class.
     *
     * @psalm-param class-string $class
     */
    public function getWidgetConfigByClass(string $class): array;

    /**
     * Returns the widget configuration for the specified property.
     *
     * @param string $property The property name.
     *
     * @return array The widget configuration for the specified property.
     */
    public function getWidgetConfigByProperty(string $property): array;

    /**
     * Returns the widget configurations for multiple properties, indexed by property name.
     *
     * @return array The widget configurations for multiple properties in an associative array format.
     *
     * ```php
     * [
     *     'property' => [
     *        'class()' => ['text-gray-100 dark:text-gray-100'],
     *     ],
     * ]
     * ```
     */
    public function getWidgetConfigByProperties(): array;

    /**
     * Returns a value indicating whether there is any validation error.
     *
     * @param string|null $property The property name. Use null to check all properties.
     *
     * @return bool Whether there is any error.
     */
    public function hasPropertyError(string $property = null): bool;

    /**
     * Returns a `true` indicating whether the property is validated successfully, `false` otherwise.
     *
     * @param string $property The property name.
     */
    public function hasPropertyValidate(string $property): bool;

    /**
     * Set errors for multiple properties.
     *
     * @param array $values The property names and the corresponding error messages.
     *
     * @psalm-param array<array<string>> $values
     *
     * ```php
     * [
     *    'username' => ['Username is required.', 'Username must contain only word characters.'],
     *    'email' => ['Email address is invalid.'],
     * ]
     */
    public function setErrors(array $values): void;
}
