<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests;

use PHPUnit\Framework\TestCase;
use UIAwesome\FormModel\BaseFormModel;
use UIAwesome\FormModel\Tests\Support\Country;
use UIAwesome\FormModel\Tests\Support\User;

/**
 * Unit tests for form-model metadata and field error behavior via {@see BaseFormModel} implementations.
 *
 * Test coverage.
 * - Adds, clears, and reads field-level validation errors, including first-error extraction.
 * - Reports field validation state and returns validation rules metadata.
 * - Resolves labels, hints, placeholders, and field configuration metadata by field path.
 * - Returns aggregated errors and summaries for populated and empty model instances.
 *
 * @copyright Copyright (C) 2024 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class FormModelTest extends TestCase
{
    public function testAddError(): void
    {
        $formModel = new Country();

        $formModel->addError('name', 'Name is required.');

        self::assertSame(
            ['Name is required.'],
            $formModel->getError('name'),
            'Should add and return the field error for the given field.',
        );
    }

    public function testGenerateLabel(): void
    {
        $formModel = new Country();

        self::assertSame(
            'Country',
            $formModel->getLabel('name'),
            'Should return the generated label for the field.',
        );
    }

    public function testGetError(): void
    {
        $formModel = new Country();

        self::assertEmpty(
            $formModel->getError('name'),
            'Should return no errors for a field without validation failures.',
        );
    }

    public function testGetErrors(): void
    {
        $formModel = new Country();

        $formModel->addError('name', 'Name is required.');

        self::assertSame(
            ['name' => ['Name is required.']],
            $formModel->getErrors(),
            'Should return all errors grouped by field.',
        );
    }

    public function testGetErrorSummary(): void
    {
        $formModel = new Country();

        $formModel->addError('name', 'Name is required.');
        $formModel->addError('postalCode', 'Postal code is required.');

        self::assertSame(
            [
                'Name is required.',
                'Postal code is required.',
            ],
            $formModel->getErrorSummary(),
            'Should return a flat error summary with all field messages.',
        );
    }

    public function testGetErrorSummaryWhenEmpty(): void
    {
        $formModel = new class extends BaseFormModel {};

        self::assertSame([], $formModel->getErrorSummary(), 'Should return an empty summary when no errors exist.');
    }

    public function testGetErrorSummaryWithFirstErrorPerField(): void
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
            'Should return only the first error message per field in summary mode.',
        );
    }

    public function testGetErrorSummaryWithFirstErrorPerFieldAndOnlySelectedFields(): void
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
            ],
            $formModel->getErrorSummary(['name'], true),
            'Should return first-error summary only for selected fields when both options are provided.',
        );
    }

    public function testGetErrorsWhenEmpty(): void
    {
        $formModel = new class extends BaseFormModel {};

        self::assertSame([], $formModel->getErrors(), 'Should return an empty error map when no errors exist.');
    }

    public function testGetFieldConfig(): void
    {
        $formModel = new User();

        self::assertSame(
            [
                'class()' => ['text-gray-100 dark:text-gray-100'],
            ],
            $formModel->getFieldConfig('name'),
            'Should return field configuration for the requested field.',
        );
    }

    public function testGetFieldConfigsWhenEmpty(): void
    {
        $formModel = new Country();

        self::assertEmpty(
            $formModel->getFieldConfigs(),
            'Should return an empty field configuration map when none are defined.',
        );
    }

    public function testGetFieldConfigWhenEmpty(): void
    {
        $formModel = new Country();

        self::assertEmpty(
            $formModel->getFieldConfig('name'),
            'Should return an empty configuration when the field has no field config.',
        );
    }

    public function testGetFirstErrors(): void
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
            $formModel->getFirstErrors(),
            'Should return only the first error per field when requested.',
        );
    }

    public function testGetFirstErrorWithNoErrors(): void
    {
        $formModel = new Country();

        self::assertEmpty(
            $formModel->getFirstError('name'),
            'Should return an empty first-error value when the field has no errors.',
        );
    }

    public function testGetHint(): void
    {
        $formModel = new Country();

        self::assertSame(
            'Enter country name',
            $formModel->getHint('name'),
            'Should return the hint for the given field.',
        );
    }

    public function testGetHints(): void
    {
        $formModel = new Country();

        self::assertSame(
            ['name' => 'Enter country name'],
            $formModel->getHints(),
            'Should return all hints keyed by field.',
        );
    }

    public function testGetHintsWhenEmpty(): void
    {
        $formModel = new class extends BaseFormModel {};

        self::assertEmpty(
            $formModel->getHints(),
            'Should return an empty hints map when no hints exist.',
        );
    }

    public function testGetLabelGeneratedWhenFieldLabelIsNotDefined(): void
    {
        $formModel = new Country();

        self::assertSame(
            'Postal Code',
            $formModel->getLabel('postalCode'),
            'Should generate a readable label when an explicit label is not defined for the field.',
        );
    }

    public function testGetLabels(): void
    {
        $formModel = new Country();

        self::assertSame(
            ['name' => 'Country'],
            $formModel->getLabels(),
            'Should return all labels keyed by field.',
        );
    }

    public function testGetLabelsWhenEmpty(): void
    {
        $formModel = new class extends BaseFormModel {};

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
            $formModel->getPlaceholder('name'),
            'Should return the placeholder for the given field.',
        );
    }

    public function testGetPlaceholders(): void
    {
        $formModel = new Country();

        self::assertSame(
            ['name' => 'Enter country name'],
            $formModel->getPlaceholders(),
            'Should return all placeholders keyed by field.',
        );
    }

    public function testGetPlaceholdersWhenEmpty(): void
    {
        $formModel = new class extends BaseFormModel {};

        self::assertEmpty(
            $formModel->getPlaceholders(),
            'Should return an empty placeholders map when no placeholders exist.',
        );
    }

    public function testGetRules(): void
    {
        $formModel = new Country();

        self::assertEmpty($formModel->getRules(), 'Should return no validation rules when none are declared.');
    }

    public function testHasError(): void
    {
        $formModel = new Country();

        self::assertFalse(
            $formModel->hasError('name'),
            'Should report no error for a field before adding one.',
        );

        $formModel->addError('name', 'Name is required.');

        self::assertTrue(
            $formModel->hasError('name'),
            'Should report an error after adding a field error.',
        );
    }

    public function testIsValidated(): void
    {
        $formModel = new Country();

        self::assertFalse(
            $formModel->isValidated('name'),
            'Should report the field as not validated before validation state is set.',
        );

        $formModel->clearError('name');

        self::assertTrue(
            $formModel->isValidated('name'),
            "Should report the field as validated after 'clearError()' marks it as explicitly validated.",
        );
    }

    public function testSetErrors(): void
    {
        $formModel = new Country();

        $formModel->setErrors(['name' => ['Name is required.']]);

        self::assertSame(
            ['Name is required.'],
            $formModel->getError('name'),
            'Should replace field errors with the values provided to setErrors().',
        );
    }
}
