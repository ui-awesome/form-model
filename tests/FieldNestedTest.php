<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use stdClass;
use UIAwesome\FormModel\Tests\Provider\FieldNestedProvider;
use UIAwesome\FormModel\Tests\Support\User;

/**
 * Unit tests for resolving nested field metadata and rules on composed form models.
 *
 * Test coverage.
 * - Rejects malformed nested field path strings containing empty segments.
 * - Resolves rules for nested fields, including nullable rule responses.
 * - Returns hints, labels, and placeholders for root and deeply nested field paths.
 * - Returns nested field configuration arrays for supported field paths.
 *
 * @copyright Copyright (C) 2024 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class FieldNestedTest extends TestCase
{
    /**
     * @param array<string, array<int, string>> $expected
     */
    #[DataProviderExternal(FieldNestedProvider::class, 'fieldConfigPathProvider')]
    public function testGetFieldConfigSeveralNestedLevels(string $field, array $expected, string $message): void
    {
        $fieldModel = new User();

        self::assertSame(
            $expected,
            $fieldModel->getFieldConfig($field),
            $message,
        );
    }

    #[DataProviderExternal(FieldNestedProvider::class, 'hintPathProvider')]
    public function testGetHintAcrossSeveralNestedLevels(string $field, string $expected, string $message): void
    {
        $fieldModel = new User();

        self::assertSame(
            $expected,
            $fieldModel->getHint($field),
            $message,
        );
    }

    public function testGetHintRejectsLeadingDotNestedFieldPath(): void
    {
        $fieldModel = new User();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid nested field path format: .profile.');

        $fieldModel->getHint('.profile');
    }

    public function testGetHintRejectsTrailingDotNestedFieldPath(): void
    {
        $fieldModel = new User();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid nested field path format: profile..');

        $fieldModel->getHint('profile.');
    }

    public function testGetHintRejectsWhitespaceOnlyNestedFieldSegment(): void
    {
        $fieldModel = new User();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid nested field path format: profile.   .');

        $fieldModel->getHint('profile.   ');
    }

    public function testGetHintRejectsWhitespaceOnlyParentFieldSegment(): void
    {
        $fieldModel = new User();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid nested field path format:    .profile.');

        $fieldModel->getHint('   .profile');
    }

    public function testGetHintTrimsIncidentalSpacesInNestedFieldPath(): void
    {
        $fieldModel = new User();

        self::assertSame(
            'Enter your bio',
            $fieldModel->getHint('profile . bio'),
            'Should resolve nested field hints when incidental spaces exist around path segments.',
        );
    }

    #[DataProviderExternal(FieldNestedProvider::class, 'labelPathProvider')]
    public function testGetLabelAcrossSeveralNestedLevels(string $field, string $expected, string $message): void
    {
        $fieldModel = new User();

        self::assertSame(
            $expected,
            $fieldModel->getLabel($field),
            $message,
        );
    }

    #[DataProviderExternal(FieldNestedProvider::class, 'placeholderPathProvider')]
    public function testGetPlaceholderAcrossSeveralNestedLevels(string $field, string $expected, string $message): void
    {
        $fieldModel = new User();

        self::assertSame(
            $expected,
            $fieldModel->getPlaceholder($field),
            $message,
        );
    }

    #[DataProviderExternal(FieldNestedProvider::class, 'rulePathProvider')]
    public function testGetRuleAcrossSeveralNestedLevels(string $field, bool $expectNull, string $message): void
    {
        $validatorObject = new stdClass();
        $fieldModel = new User($validatorObject);

        if ($expectNull) {
            self::assertNull($fieldModel->getRule($field), $message);

            return;
        }

        self::assertSame(
            [$validatorObject],
            $fieldModel->getRule($field),
            $message,
        );
    }
}
