<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests\Attribute;

use PHPUnit\Framework\TestCase;
use UIAwesome\FormModel\BaseFormModel;
use UIAwesome\FormModel\Tests\Support\{AttributePriorityForm, Country};

/**
 * Unit tests for hint metadata resolution.
 */
final class HintTest extends TestCase
{
    public function testGetHintFallsBackToMapMetadataWhenAttributeIsMissing(): void
    {
        $formModel = new AttributePriorityForm();

        self::assertSame(
            'Map only hint',
            $formModel->getHint('fallback'),
            'Should resolve hints from map metadata when no hint attribute is declared.',
        );
    }

    public function testGetHintForDeclaredField(): void
    {
        $formModel = new Country();

        self::assertSame(
            'Enter country name',
            $formModel->getHint('name'),
            'Should return hint metadata for fields with configured hints.',
        );
    }

    public function testGetHintsReturnsFieldKeyedMap(): void
    {
        $formModel = new Country();

        self::assertSame(
            [
                'name' => 'Enter country name',
            ],
            $formModel->getHints(),
            'Should return all hint metadata keyed by field names.',
        );
    }

    public function testGetHintsWhenModelDefinesNoHints(): void
    {
        $formModel = new class extends BaseFormModel {};

        self::assertEmpty(
            $formModel->getHints(),
            'Should return an empty hint map when the model exposes no hint metadata.',
        );
    }

    public function testGetHintUsesAttributeBeforeMapMetadata(): void
    {
        $formModel = new AttributePriorityForm();

        self::assertSame(
            'Attribute hint',
            $formModel->getHint('name'),
            'Should resolve hints from property attributes before map metadata.',
        );
    }
}
