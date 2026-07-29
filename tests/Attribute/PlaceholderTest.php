<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests\Attribute;

use PHPUnit\Framework\TestCase;
use UIAwesome\FormModel\BaseFormModel;
use UIAwesome\FormModel\Tests\Support\{AttributePriorityForm, Country};

/**
 * Unit tests for placeholder metadata resolution.
 */
final class PlaceholderTest extends TestCase
{
    public function testGetPlaceholderFallsBackToMapMetadataWhenAttributeIsMissing(): void
    {
        $formModel = new AttributePriorityForm();

        self::assertSame(
            'Map only placeholder',
            $formModel->getPlaceholder('fallback'),
            'Should resolve placeholders from map metadata when no placeholder attribute is declared.',
        );
    }

    public function testGetPlaceholderForDeclaredField(): void
    {
        $formModel = new Country();

        self::assertSame(
            'Enter country name',
            $formModel->getPlaceholder('name'),
            'Should return placeholder metadata for fields with configured placeholders.',
        );
    }

    public function testGetPlaceholdersReturnsFieldKeyedMap(): void
    {
        $formModel = new Country();

        self::assertSame(
            [
                'name' => 'Enter country name',
            ],
            $formModel->getPlaceholders(),
            'Should return all placeholder metadata keyed by field names.',
        );
    }

    public function testGetPlaceholdersWhenModelDefinesNone(): void
    {
        $formModel = new class extends BaseFormModel {};

        self::assertEmpty(
            $formModel->getPlaceholders(),
            'Should return an empty placeholder map when the model exposes no placeholder metadata.',
        );
    }

    public function testGetPlaceholderUsesAttributeBeforeMapMetadata(): void
    {
        $formModel = new AttributePriorityForm();

        self::assertSame(
            'Attribute placeholder',
            $formModel->getPlaceholder('name'),
            'Should resolve placeholders from property attributes before map metadata.',
        );
    }
}
