<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests;

use PHPUnit\Framework\TestCase;
use UIAwesome\FormModel\AbstractFormModel;
use UIAwesome\FormModel\Tests\Support\Country;
use UIAwesome\FormModel\Tests\Support\User;

/**
 * Unit tests for form-model metadata and field error behavior via {@see AbstractFormModel} implementations.
 *
 * Test coverage.
 * - Adds, clears, and reads property-level validation errors, including first-error extraction.
 * - Reports property validation state and returns validation rules metadata.
 * - Resolves labels, hints, placeholders, and field configuration metadata by property path.
 * - Returns aggregated errors and summaries for populated and empty model instances.
 *
 * @copyright Copyright (C) 2024 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class FormModelTest extends TestCase
{
    public function testAddPropertyError(): void
    {
        $formModel = new Country();

        $formModel->addPropertyError('name', 'Name is required.');

        self::assertSame(
            ['Name is required.'],
            $formModel->getPropertyError('name'),
            'Should add and return the property error for the given field.',
        );
    }

    public function testGenerateLabel(): void
    {
        $formModel = new Country();

        self::assertSame(
            'Country',
            $formModel->getLabelByProperty('name'),
            'Should return the generated label for the property.',
        );
    }

    public function testGetErrors(): void
    {
        $formModel = new Country();

        $formModel->addPropertyError('name', 'Name is required.');

        self::assertSame(
            ['name' => ['Name is required.']],
            $formModel->getErrors(),
            'Should return all errors grouped by property.',
        );
    }

    public function testGetErrorSummary(): void
    {
        $formModel = new Country();

        $formModel->addPropertyError('name', 'Name is required.');
        $formModel->addPropertyError('postalCode', 'Postal code is required.');

        self::assertSame(
            [
                'Name is required.',
                'Postal code is required.',
            ],
            $formModel->getErrorSummary(),
            'Should return a flat error summary with all property messages.',
        );
    }

    public function testGetErrorSummaryWhenEmpty(): void
    {
        $formModel = new class extends AbstractFormModel {};

        self::assertSame([], $formModel->getErrorSummary(), 'Should return an empty summary when no errors exist.');
    }

    public function testGetErrorSummaryWithFirstErrorPerProperty(): void
    {
        $formModel = new Country();

        $formModel->setErrors(
            [
                'name' => ['The field is required', 'Invalid name'],
                'postalCode' => ['Invalid postal code', 'The field is required'],
            ],
        );

        self::assertSame(
            [
                'name' => 'The field is required',
                'postalCode' => 'Invalid postal code',
            ],
            $formModel->getErrorSummary(first: true),
            'Should return only the first error message per property in summary mode.',
        );
    }

    public function testGetErrorsWhenEmpty(): void
    {
        $formModel = new class extends AbstractFormModel {};

        self::assertSame([], $formModel->getErrors(), 'Should return an empty error map when no errors exist.');
    }

    public function testGetErrorsWithFirstErrorPerProperty(): void
    {
        $formModel = new Country();

        $formModel->setErrors(
            [
                'name' => ['The field is required', 'Invalid name'],
                'postalCode' => ['The field is required', 'Invalid postal code'],
            ],
        );

        self::assertSame(
            [
                'name' => 'The field is required',
                'postalCode' => 'The field is required',
            ],
            $formModel->getErrors(first: true),
            'Should return only the first error per property when requested.',
        );
    }

    public function testGetHint(): void
    {
        $formModel = new Country();

        self::assertSame(
            'Enter country name',
            $formModel->getHintByProperty('name'),
            'Should return the hint for the given property.',
        );
    }

    public function testGetHints(): void
    {
        $formModel = new Country();

        self::assertSame(
            ['name' => 'Enter country name'],
            $formModel->getHints(),
            'Should return all hints keyed by property.',
        );
    }

    public function testGetHintsWhenEmpty(): void
    {
        $formModel = new class extends AbstractFormModel {};

        self::assertEmpty(
            $formModel->getHints(),
            'Should return an empty hints map when no hints exist.',
        );
    }

    public function testGetLabel(): void
    {
        $formModel = new Country();

        self::assertSame(
            'Country',
            $formModel->getLabelByProperty('name'),
            'Should return the label for the given property.',
        );
    }

    public function testGetLabels(): void
    {
        $formModel = new Country();

        self::assertSame(
            ['name' => 'Country'],
            $formModel->getLabels(),
            'Should return all labels keyed by property.',
        );
    }

    public function testGetLabelsWhenEmpty(): void
    {
        $formModel = new class extends AbstractFormModel {};

        self::assertSame(
            [],
            $formModel->getLabels(),
            'Should return an empty labels map when no labels exist.',
        );
    }

    public function testGetPlaceholder(): void
    {
        $formModel = new Country();

        self::assertSame(
            'Enter country name',
            $formModel->getPlaceholderByProperty('name'),
            'Should return the placeholder for the given property.',
        );
    }

    public function testGetPlaceholders(): void
    {
        $formModel = new Country();

        self::assertSame(
            ['name' => 'Enter country name'],
            $formModel->getPlaceholders(),
            'Should return all placeholders keyed by property.',
        );
    }

    public function testGetPlaceholdersWhenEmpty(): void
    {
        $formModel = new class extends AbstractFormModel {};

        self::assertEmpty(
            $formModel->getPlaceholders(),
            'Should return an empty placeholders map when no placeholders exist.',
        );
    }

    public function testGetPropertyError(): void
    {
        $formModel = new Country();

        self::assertEmpty(
            $formModel->getPropertyError('name'),
            'Should return no errors for a property without validation failures.',
        );
    }

    public function testGetPropertyErrorWithFirstErrorOnly(): void
    {
        $formModel = new Country();

        self::assertEmpty(
            $formModel->getPropertyError('name', true),
            'Should return an empty first-error value when the property has no errors.',
        );
    }

    public function testGetRules(): void
    {
        $formModel = new Country();

        self::assertEmpty($formModel->getRules(), 'Should return no validation rules when none are declared.');
    }

    public function testGetFieldConfigByProperty(): void
    {
        $formModel = new User();

        self::assertSame(
            [
                'class()' => ['text-gray-100 dark:text-gray-100'],
            ],
            $formModel->getFieldConfigByProperty('name'),
            'Should return field configuration for the requested property.',
        );
    }

    public function testGetFieldConfigByPropertyWhenEmpty(): void
    {
        $formModel = new Country();

        self::assertEmpty(
            $formModel->getFieldConfigByProperty('name'),
            'Should return an empty configuration when the property has no field config.',
        );
    }

    public function testGetFieldConfigByPropertiesWhenEmpty(): void
    {
        $formModel = new Country();

        self::assertEmpty(
            $formModel->getFieldConfigByProperties(),
            'Should return an empty field configuration map when none are defined.',
        );
    }

    public function testHasPropertyError(): void
    {
        $formModel = new Country();

        self::assertFalse(
            $formModel->hasPropertyError('name'),
            'Should report no error for a property before adding one.',
        );

        $formModel->addPropertyError('name', 'Name is required.');

        self::assertTrue(
            $formModel->hasPropertyError('name'),
            'Should report an error after adding a property error.',
        );
    }

    public function testHasPropertyValidate(): void
    {
        $formModel = new Country();

        self::assertFalse(
            $formModel->hasPropertyValidate('name'),
            'Should report the property as not validated before validation state is set.',
        );

        $formModel->clearError('name');

        self::assertTrue(
            $formModel->hasPropertyValidate('name'),
            'Should report the property as validated after clearing its error state.',
        );
    }

    public function testSetErrors(): void
    {
        $formModel = new Country();

        $formModel->setErrors(['name' => ['Name is required.']]);

        self::assertSame(
            ['Name is required.'],
            $formModel->getPropertyError('name'),
            'Should replace property errors with the values provided to setErrors().',
        );
    }
}
