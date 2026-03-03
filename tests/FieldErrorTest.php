<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests;

use PHPUnit\Framework\TestCase;
use UIAwesome\FormModel\FieldError;

/**
 * Unit tests for field-scoped error storage and summary behavior in {@see FieldError}.
 *
 * Test coverage.
 * - Adds, sets, clears, and reads errors for individual fields and full error collections.
 * - Handles empty-state reads without raising errors.
 * - Reports validation and error presence globally and for specific fields.
 * - Returns first-error views and full-error views for getters and summary methods.
 *
 * @copyright Copyright (C) 2024 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class FieldErrorTest extends TestCase
{
    public function testAdd(): void
    {
        $fieldError = new FieldError();

        $fieldError->add('username', 'Invalid username.');

        self::assertTrue(
            $fieldError->has('username'),
            'Should report the field as having an error after adding one.',
        );
        self::assertSame(
            'Invalid username.',
            $fieldError->getField('username', true),
            'Should return the first error message for the field.',
        );
    }

    public function testClear(): void
    {
        $fieldError = new FieldError();

        $fieldError->add('username', 'Username is required');
        $fieldError->add('email', 'Email is required');

        self::assertSame(
            [
                'username' => ['Username is required'],
                'email' => ['Email is required'],
            ],
            $fieldError->get(),
            'Should return all errors before clearing them.',
        );

        $fieldError->clear();

        self::assertEmpty(
            $fieldError->get(),
            "Should remove all errors when 'clear()' is called without a field.",
        );
    }

    public function testClearWithField(): void
    {
        $fieldError = new FieldError();

        $fieldError->add('username', 'Username is required');
        $fieldError->add('email', 'Email is required');

        self::assertSame(
            [
                'username' => ['Username is required'],
                'email' => ['Email is required'],
            ],
            $fieldError->get(),
            'Should keep both field errors before clearing one field.',
        );

        $fieldError->clear('username');

        self::assertSame(
            [
                'email' => ['Email is required'],
            ],
            $fieldError->get(),
            'Should clear only the requested field errors.',
        );
    }

    public function testGet(): void
    {
        $fieldError = new FieldError();

        self::assertEmpty(
            $fieldError->get(),
            'Should return an empty error map before any errors are set.',
        );

        $fieldError->set(
            [
                'username' => ['The field is required', 'Invalid username'],
                'email' => ['Invalid email', 'The field is required'],
            ],
        );

        self::assertSame(
            [
                'username' => ['The field is required', 'Invalid username'],
                'email' => ['Invalid email', 'The field is required'],
            ],
            $fieldError->get(),
            'Should return all stored errors grouped by field.',
        );
        self::assertSame(
            [
                'username' => 'The field is required',
                'email' => 'Invalid email',
            ],
            $fieldError->get(true),
            'Should return only the first error per field when requested.',
        );
    }

    public function testGetAll(): void
    {
        $fieldError = new FieldError();

        $fieldError->set(
            [
                'username' => ['The field is required', 'Invalid username'],
                'email' => ['Invalid email', 'The field is required'],
            ],
        );

        self::assertSame(
            [
                'username' => ['The field is required', 'Invalid username'],
                'email' => ['Invalid email', 'The field is required'],
            ],
            $fieldError->getAll(),
            'Should return all stored fields and preserve all messages for each field.',
        );
    }

    public function testGetForField(): void
    {
        $fieldError = new FieldError();

        $fieldError->set(
            [
                'username' => ['Invalid username', 'The field is required'],
                'email' => ['Invalid email', 'The field is required'],
            ],
        );

        self::assertSame(
            ['Invalid username', 'The field is required'],
            $fieldError->getForField('username'),
            'Should return all stored messages for the requested field without truncation.',
        );
    }

    public function testGetField(): void
    {
        $fieldError = new FieldError();

        $fieldError->set(
            [
                'username' => ['Invalid username', 'The field is required'],
                'email' => ['Invalid email', 'The field is required'],
            ],
        );

        self::assertSame(
            ['Invalid username', 'The field is required'],
            $fieldError->getField('username'),
            'Should return all errors for the requested field.',
        );
    }

    public function testGetFieldWhenEmpty(): void
    {
        $fieldError = new FieldError();

        self::assertEmpty(
            $fieldError->getField('username'),
            'Should return no errors for a field with no entries.',
        );
        self::assertEmpty(
            $fieldError->getField('username', true),
            'Should return no first error for a field with no entries.',
        );
    }

    public function testGetFieldWithFirstErrorOnly(): void
    {
        $fieldError = new FieldError();

        $fieldError->set(
            [
                'username' => ['Invalid username', 'The field is required'],
                'email' => ['Invalid email', 'The field is required'],
            ],
        );

        self::assertNotSame(
            'The field is required',
            $fieldError->getField('username', true),
            'Should not return non-first errors when first-only mode is enabled.',
        );
        self::assertSame(
            'Invalid username',
            $fieldError->getField('username', true),
            'Should return the first error for the requested field.',
        );
    }

    public function testGetSummary(): void
    {
        $fieldError = new FieldError();

        $fieldError->set(
            [
                'username' => ['Invalid username'],
                'email' => ['Invalid email'],
            ],
        );

        self::assertSame(
            ['Invalid username', 'Invalid email'],
            $fieldError->getSummary(),
            'Should return a flat summary with all error messages.',
        );
    }

    public function testGetSummaryWhenEmpty(): void
    {
        $fieldError = new FieldError();

        self::assertEmpty(
            $fieldError->getSummary(),
            'Should return an empty summary when no errors exist.',
        );
    }

    public function testGetSummaryWithFirstErrorPerField(): void
    {
        $fieldError = new FieldError();

        $fieldError->set(
            [
                'username' => ['The field is required', 'Invalid username'],
                'email' => ['Invalid email', 'The field is required'],
            ],
        );

        self::assertSame(
            [
                'username' => 'The field is required',
                'email' => 'Invalid email',
            ],
            $fieldError->getSummary(first: true),
            'Should return only the first error per field in summary mode.',
        );
    }

    public function testGetSummaryWithFirstErrorPerFieldAndOnlySelectedFields(): void
    {
        $fieldError = new FieldError();

        $fieldError->set(
            [
                'username' => ['The field is required', 'Invalid username'],
                'email' => ['Invalid email', 'The field is required'],
            ],
        );

        self::assertSame(
            [
                'username' => 'The field is required',
            ],
            $fieldError->getSummary(['username'], true),
            'Should filter first-error summary by selected fields when both options are provided.',
        );
    }

    public function testGetSummaryWithOnlySelectedField(): void
    {
        $fieldError = new FieldError();

        $fieldError->set(
            [
                'username' => ['Invalid username'],
                'email' => ['Invalid email'],
            ],
        );

        self::assertSame(
            ['Invalid username'],
            $fieldError->getSummary(['username']),
            'Should return summary messages only for the selected fields.',
        );
    }

    public function testGetSummaryWithSelectedFieldAndFirstErrorPerField(): void
    {
        $fieldError = new FieldError();

        $fieldError->set(
            [
                'username' => ['The field is required', 'Invalid username'],
                'email' => ['Invalid email', 'The field is required'],
            ],
        );

        self::assertSame(
            ['username' => 'The field is required'],
            $fieldError->getSummary(['username'], true),
            'Should return first errors only for selected fields.',
        );
    }

    public function testGetWhenEmpty(): void
    {
        $fieldError = new FieldError();

        self::assertEmpty(
            $fieldError->get(),
            'Should return no errors when none were added.',
        );
        self::assertEmpty(
            $fieldError->get(true),
            'Should return no first errors when none were added.',
        );
    }

    public function testGetWithFirstErrorPerField(): void
    {
        $fieldError = new FieldError();

        $fieldError->set(
            [
                'username' => ['Invalid username', 'The field is required'],
                'email' => ['Invalid email', 'The field is required'],
            ],
        );

        self::assertSame(
            [
                'username' => 'Invalid username',
                'email' => 'Invalid email',
            ],
            $fieldError->get(true),
            'Should return first errors for each field when first-only mode is enabled.',
        );
    }

    public function testHas(): void
    {
        $fieldError = new FieldError();

        self::assertFalse(
            $fieldError->has(),
            'Should report no errors before any are added.',
        );

        $fieldError->add('username', 'Invalid username');

        self::assertTrue(
            $fieldError->has(),
            'Should report that errors exist after adding one.',
        );
    }

    public function testHasValidate(): void
    {
        $fieldError = new FieldError();

        self::assertFalse(
            $fieldError->isValidated('username'),
            'Should report the field as not validated before validation state is set.',
        );

        $fieldError->clear('username');

        self::assertTrue(
            $fieldError->isValidated('username'),
            'Should report the field as validated after clearing its error state.',
        );
    }

    public function testHasWithSpecificField(): void
    {
        $fieldError = new FieldError();

        self::assertFalse(
            $fieldError->has('username'),
            'Should report no error for the field before adding one.',
        );

        $fieldError->add('username', 'Invalid username');

        self::assertTrue(
            $fieldError->has('username'),
            'Should report an error for the field after adding one.',
        );
    }

    public function testSet(): void
    {
        $fieldError = new FieldError();

        $errorContent = [
            'username' => ['Invalid username'],
        ];

        $fieldError->clear();

        self::assertEmpty(
            $fieldError->getField('username'),
            'Should start with no stored errors for the field.',
        );

        $fieldError->set($errorContent);

        self::assertTrue(
            $fieldError->has('username'),
            'Should report an error after setting field errors.',
        );
        self::assertSame(
            'Invalid username',
            $fieldError->getField('username', true),
            'Should return the first error value set for the field.',
        );
    }
}
