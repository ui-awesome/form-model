<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests;

use PHPUnit\Framework\TestCase;
use UIAwesome\FormModel\FieldError;

/**
 * Unit tests for property-scoped error storage and summary behavior in {@see FieldError}.
 *
 * Test coverage.
 * - Adds, sets, clears, and reads errors for individual properties and full error collections.
 * - Handles empty-state reads without raising errors.
 * - Reports validation and error presence globally and for specific properties.
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
            'Should report the property as having an error after adding one.',
        );
        self::assertSame(
            'Invalid username.',
            $fieldError->getProperty('username', true),
            'Should return the first error message for the property.',
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
            "Should remove all errors when 'clear()' is called without a property.",
        );
    }

    public function testClearWithProperty(): void
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
            'Should keep both property errors before clearing one property.',
        );

        $fieldError->clear('username');

        self::assertSame(
            [
                'email' => ['Email is required'],
            ],
            $fieldError->get(),
            'Should clear only the requested property errors.',
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
            'Should return all stored errors grouped by property.',
        );
        self::assertSame(
            [
                'username' => 'The field is required',
                'email' => 'Invalid email',
            ],
            $fieldError->get(true),
            'Should return only the first error per property when requested.',
        );
    }

    public function testGetProperty(): void
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
            $fieldError->getProperty('username'),
            'Should return all errors for the requested property.',
        );
    }

    public function testGetPropertyWhenEmpty(): void
    {
        $fieldError = new FieldError();

        self::assertEmpty(
            $fieldError->getProperty('username'),
            'Should return no errors for a property with no entries.',
        );
        self::assertEmpty(
            $fieldError->getProperty('username', true),
            'Should return no first error for a property with no entries.',
        );
    }

    public function testGetPropertyWithFirstErrorOnly(): void
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
            $fieldError->getProperty('username', true),
            'Should not return non-first errors when first-only mode is enabled.',
        );
        self::assertSame(
            'Invalid username',
            $fieldError->getProperty('username', true),
            'Should return the first error for the requested property.',
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

    public function testGetSummaryWithFirstErrorPerProperty(): void
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
            'Should return only the first error per property in summary mode.',
        );
    }

    public function testGetSummaryWithFirstErrorPerPropertyAndOnlySelectedProperties(): void
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
            'Should filter first-error summary by selected properties when both options are provided.',
        );
    }

    public function testGetSummaryWithOnlySelectedProperty(): void
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
            'Should return summary messages only for the selected properties.',
        );
    }

    public function testGetSummaryWithSelectedPropertyAndFirstErrorPerProperty(): void
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
            'Should return first errors only for selected properties.',
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

    public function testGetWithFirstErrorPerProperty(): void
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
            'Should return first errors for each property when first-only mode is enabled.',
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
            $fieldError->hasValidate('username'),
            'Should report the property as not validated before validation state is set.',
        );

        $fieldError->clear('username');

        self::assertTrue(
            $fieldError->hasValidate('username'),
            'Should report the property as validated after clearing its error state.',
        );
    }

    public function testHasWithSpecificProperty(): void
    {
        $fieldError = new FieldError();

        self::assertFalse(
            $fieldError->has('username'),
            'Should report no error for the property before adding one.',
        );

        $fieldError->add('username', 'Invalid username');

        self::assertTrue(
            $fieldError->has('username'),
            'Should report an error for the property after adding one.',
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
            $fieldError->getProperty('username'),
            'Should start with no stored errors for the property.',
        );

        $fieldError->set($errorContent);

        self::assertTrue(
            $fieldError->has('username'),
            'Should report an error after setting property errors.',
        );
        self::assertSame(
            'Invalid username',
            $fieldError->getProperty('username', true),
            'Should return the first error value set for the property.',
        );
    }
}
