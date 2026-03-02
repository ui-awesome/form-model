<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use UIAwesome\FormModel\Tests\Support\User;

/**
 * Unit tests for resolving nested field metadata and rules on composed form models.
 *
 * Test coverage.
 * - Rejects malformed nested property strings containing empty path segments.
 * - Resolves rules for nested properties, including nullable rule responses.
 * - Returns hints, labels, and placeholders for root and deeply nested property paths.
 * - Returns nested field configuration arrays for supported property paths.
 *
 * @copyright Copyright (C) 2024 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class FieldNestedTest extends TestCase
{
    public function testGetFieldConfigSeveralNestedLevels(): void
    {
        $fieldModel = new User();

        self::assertSame(
            [
                'class()' => ['text-gray-100 dark:text-gray-100'],
            ],
            $fieldModel->getFieldConfig('name'),
            'Should return field configuration for the root property.',
        );
        self::assertSame(
            [
                'class()' => ['text-green-100 dark:text-green-100'],
            ],
            $fieldModel->getFieldConfig('profile.bio'),
            'Should return field configuration for the nested profile property.',
        );
        self::assertSame(
            [
                'class()' => ['text-blue-100 dark:text-blue-100'],
            ],
            $fieldModel->getFieldConfig('profile.address.street'),
            'Should return field configuration for the deeply nested street property.',
        );
        self::assertSame(
            [
                'class()' => ['text-red-100 dark:text-red-100'],
            ],
            $fieldModel->getFieldConfig('profile.address.city'),
            'Should return field configuration for the deeply nested city property.',
        );
    }

    public function testGetHintAcrossSeveralNestedLevels(): void
    {
        $fieldModel = new User();

        self::assertSame(
            'Enter your name',
            $fieldModel->getHint('name'),
            'Should return the root hint value.',
        );
        self::assertSame(
            'Enter your bio',
            $fieldModel->getHint('profile.bio'),
            'Should return the nested profile hint value.',
        );
        self::assertSame(
            'Enter street name',
            $fieldModel->getHint('profile.address.street'),
            'Should return the deeply nested street hint value.',
        );
        self::assertSame(
            'Enter city name',
            $fieldModel->getHint('profile.address.city'),
            'Should return the deeply nested city hint value.',
        );
        self::assertSame(
            'Enter country name',
            $fieldModel->getHint('profile.address.country.name'),
            'Should return the deeply nested country hint value.',
        );
    }

    public function testGetHintRejectsLeadingDotNestedProperty(): void
    {
        $fieldModel = new User();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid nested property format: .profile.');

        $fieldModel->getHint('.profile');
    }

    public function testGetHintRejectsTrailingDotNestedProperty(): void
    {
        $fieldModel = new User();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid nested property format: profile..');

        $fieldModel->getHint('profile.');
    }

    public function testGetHintRejectsWhitespaceOnlyNestedSegment(): void
    {
        $fieldModel = new User();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid nested property format: profile.   .');

        $fieldModel->getHint('profile.   ');
    }

    public function testGetHintRejectsWhitespaceOnlyParentSegment(): void
    {
        $fieldModel = new User();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid nested property format:    .profile.');

        $fieldModel->getHint('   .profile');
    }

    public function testGetLabelAcrossSeveralNestedLevels(): void
    {
        $fieldModel = new User();

        self::assertSame(
            'Name',
            $fieldModel->getLabel('name'),
            'Should return the root label value.',
        );
        self::assertSame(
            'Bio',
            $fieldModel->getLabel('profile.bio'),
            'Should return the nested profile label value.',
        );
        self::assertSame(
            'Street',
            $fieldModel->getLabel('profile.address.street'),
            'Should return the deeply nested street label value.',
        );
        self::assertSame(
            'City',
            $fieldModel->getLabel('profile.address.city'),
            'Should return the deeply nested city label value.',
        );
        self::assertSame(
            'Country',
            $fieldModel->getLabel('profile.address.country.name'),
            'Should return the deeply nested country label value.',
        );
    }

    public function testGetPlaceholderAcrossSeveralNestedLevels(): void
    {
        $fieldModel = new User();

        self::assertSame(
            'Enter your name',
            $fieldModel->getPlaceholder('name'),
            'Should return the root placeholder value.',
        );
        self::assertSame(
            'Enter your bio',
            $fieldModel->getPlaceholder('profile.bio'),
            'Should return the nested profile placeholder value.',
        );
        self::assertSame(
            'Enter street name',
            $fieldModel->getPlaceholder('profile.address.street'),
            'Should return the deeply nested street placeholder value.',
        );
        self::assertSame(
            'Enter city name',
            $fieldModel->getPlaceholder('profile.address.city'),
            'Should return the deeply nested city placeholder value.',
        );
        self::assertSame(
            'Enter country name',
            $fieldModel->getPlaceholder('profile.address.country.name'),
            'Should return the deeply nested country placeholder value.',
        );
    }

    public function testGetRuleAcrossSeveralNestedLevels(): void
    {
        $validatorObject = new stdClass();
        $fieldModel = new User($validatorObject);

        self::assertSame(
            [$validatorObject],
            $fieldModel->getRule('name'),
            'Should return validators for the root property.',
        );
        self::assertNull(
            $fieldModel->getRule('profile.address.city'),
            'Should return null when no validators are configured for the nested property.',
        );
        self::assertSame(
            [$validatorObject],
            $fieldModel->getRule('profile.bio'),
            'Should return validators for the nested profile property.',
        );
        self::assertSame(
            [$validatorObject],
            $fieldModel->getRule('profile.address.street'),
            'Should return validators for the deeply nested street property.',
        );
    }
}
