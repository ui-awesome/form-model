<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests\Attribute;

use PHPUnit\Framework\TestCase;
use UIAwesome\FormModel\Tests\Support\AttributePriorityForm;
use UIAwesome\FormModel\Tests\Support\Country;
use UIAwesome\FormModel\Tests\Support\User;

/**
 * Unit tests for field configuration metadata resolution.
 *
 * Test coverage.
 * - Falls back to `getFieldConfigs()` maps when attributes are missing.
 * - Prioritizes property attributes over `getFieldConfigs()` maps.
 * - Returns an empty field configuration map when the model defines none.
 * - Returns empty field configuration for fields without metadata.
 * - Returns field configuration for declared fields.
 *
 * @copyright Copyright (C) 2026 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class FieldConfigTest extends TestCase
{
    public function testGetFieldConfigFallsBackToMapMetadataWhenAttributeIsMissing(): void
    {
        $formModel = new AttributePriorityForm();

        self::assertSame(
            [
                'class' => ['map-only'],
            ],
            $formModel->getFieldConfig('fallback'),
            'Should resolve field configuration from map metadata when no attribute is declared.',
        );
    }

    public function testGetFieldConfigForDeclaredField(): void
    {
        $formModel = new User();

        self::assertSame(
            [
                'class()' => ['text-gray-100 dark:text-gray-100'],
            ],
            $formModel->getFieldConfig('name'),
            'Should return field configuration metadata for declared fields.',
        );
    }

    public function testGetFieldConfigsWhenModelDoesNotDefineAny(): void
    {
        $formModel = new Country();

        self::assertEmpty(
            $formModel->getFieldConfigs(),
            'Should return an empty field configuration map when the model defines no entries.',
        );
    }

    public function testGetFieldConfigUsesAttributeBeforeMapMetadata(): void
    {
        $formModel = new AttributePriorityForm();

        self::assertSame(
            [
                'class' => ['attribute-priority'],
            ],
            $formModel->getFieldConfig('name'),
            'Should resolve field configuration from property attributes before map metadata.',
        );
    }

    public function testGetFieldConfigWhenMetadataIsNotDefined(): void
    {
        $formModel = new Country();

        self::assertEmpty(
            $formModel->getFieldConfig('name'),
            'Should return an empty configuration when no field config metadata is defined.',
        );
    }
}
