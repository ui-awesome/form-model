<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests\Attribute;

use PHPUnit\Framework\TestCase;
use UIAwesome\FormModel\BaseFormModel;
use UIAwesome\FormModel\Tests\Support\AttributePriorityForm;
use UIAwesome\FormModel\Tests\Support\Country;

/**
 * Unit tests for label metadata resolution and fallback generation.
 *
 * Test coverage.
 * - Falls back to `getLabels()` map metadata when attributes are missing.
 * - Generates labels when explicit field labels are not configured.
 * - Prioritizes property attributes over `getLabels()` map metadata.
 * - Returns all labels as a field-keyed map.
 * - Returns labels for fields with declared metadata.
 *
 * @copyright Copyright (C) 2026 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class LabelTest extends TestCase
{
    public function testGetLabelFallsBackToMapMetadataWhenAttributeIsMissing(): void
    {
        $formModel = new AttributePriorityForm();

        self::assertSame(
            'Map only label',
            $formModel->getLabel('fallback'),
            'Should resolve labels from map metadata when no label attribute is declared.',
        );
    }

    public function testGetLabelForDeclaredField(): void
    {
        $formModel = new Country();

        self::assertSame(
            'Country',
            $formModel->getLabel('name'),
            'Should return the configured label for fields that define label metadata.',
        );
    }

    public function testGetLabelGeneratedWhenFieldLabelIsNotDefined(): void
    {
        $formModel = new Country();

        self::assertSame(
            'Postal Code',
            $formModel->getLabel('postalCode'),
            'Should generate a readable label when no explicit label metadata is defined.',
        );
    }

    public function testGetLabelsReturnsFieldKeyedMap(): void
    {
        $formModel = new Country();

        self::assertSame(
            [
                'name' => 'Country',
            ],
            $formModel->getLabels(),
            'Should return all label metadata keyed by field names.',
        );
    }

    public function testGetLabelsWhenModelDefinesNoLabels(): void
    {
        $formModel = new class extends BaseFormModel {};

        self::assertEmpty(
            $formModel->getLabels(),
            'Should return an empty label map when the model exposes no label metadata.',
        );
    }

    public function testGetLabelUsesAttributeBeforeMapMetadata(): void
    {
        $formModel = new AttributePriorityForm();

        self::assertSame(
            'Attribute label',
            $formModel->getLabel('name'),
            'Should resolve labels from property attributes before map metadata.',
        );
    }
}
