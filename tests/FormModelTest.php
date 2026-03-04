<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests;

use PHPUnit\Framework\TestCase;
use UIAwesome\FormModel\BaseFormModel;
use UIAwesome\FormModel\Tests\Support\Country;

/**
 * Unit tests for validation error and rule behavior in {@see BaseFormModel}.
 *
 * Test coverage.
 * - Adds, clears, and reads field-level validation errors, including first-error extraction.
 * - Reports field validation state transitions for specific fields.
 * - Returns aggregated errors and summaries for populated and empty model instances.
 * - Returns validation rules metadata for models without explicit rules.
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
            [
                'Name is required.',
            ],
            $formModel->getError('name'),
            'Should add and return the field error for the given field.',
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
            [
                'name' => ['Name is required.'],
            ],
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

        self::assertEmpty(
            $formModel->getErrorSummary(),
            'Should return an empty summary when no errors exist.',
        );
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

        self::assertEmpty(
            $formModel->getErrors(),
            'Should return an empty error map when no errors exist.',
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

    public function testGetRules(): void
    {
        $formModel = new Country();

        self::assertEmpty(
            $formModel->getRules(),
            'Should return no validation rules when none are declared.',
        );
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
            [
                'Name is required.',
            ],
            $formModel->getError('name'),
            'Should replace field errors with the values provided to setErrors().',
        );
    }
}
