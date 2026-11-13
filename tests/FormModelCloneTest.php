<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests;

use PHPUnit\Framework\TestCase;
use UIAwesome\FormModel\BaseFormModel;

/**
 * Unit tests for independent error state in cloned {@see BaseFormModel} instances.
 */
final class FormModelCloneTest extends TestCase
{
    public function testCloneAfterReadingEmptyErrors(): void
    {
        $source = new class extends BaseFormModel {};

        self::assertSame(
            [],
            $source->getErrors(),
            'Initial errors must be empty.',
        );

        $clone = clone $source;

        $source->addError('email', 'Source error.');

        self::assertSame(
            [],
            $clone->getErrors(),
            'Source writes must not change the clone.',
        );

        $clone->addError('name', 'Clone error.');

        self::assertSame(
            ['email' => ['Source error.']],
            $source->getErrors(),
            'Clone writes must not change the source.',
        );
    }

    public function testCloneBeforeErrorStorageInitialization(): void
    {
        $source = new class extends BaseFormModel {};

        $clone = clone $source;

        $clone->addError('email', 'Clone error.');
        $source->addError('name', 'Source error.');

        self::assertSame(
            ['email' => ['Clone error.']],
            $clone->getErrors(),
            'Clone errors must stay independent.',
        );
        self::assertSame(
            ['name' => ['Source error.']],
            $source->getErrors(),
            'Source errors must stay independent.',
        );
    }

    public function testClonePreservesErrorsAndValidationState(): void
    {
        $source = new class extends BaseFormModel {};

        $source->setErrors(
            [
                'email' => [
                    'First error.',
                    'Second error.',
                ],
                'name' => [],
            ],
        );

        $clone = clone $source;

        self::assertSame(
            $source->getErrors(),
            $clone->getErrors(),
            'Existing messages must be copied.',
        );
        self::assertTrue(
            $clone->isValidated('name'),
            'Validated fields must be preserved.',
        );
        self::assertFalse(
            $clone->isValidated('missing'),
            'Untouched fields must remain unvalidated.',
        );

        $clone->clearError('email');

        self::assertSame(
            ['First error.', 'Second error.'],
            $source->getError('email'),
            'Field clearing must stay local.',
        );
        self::assertTrue(
            $clone->isValidated('email'),
            'Cleared fields must remain validated.',
        );
        self::assertFalse(
            $source->isValidated('email'),
            'Source validation state must not change.',
        );

        $source->setErrors(['other' => ['Replacement error.']]);

        self::assertTrue(
            $clone->isValidated('name'),
            'Replacing source errors must not remove clone validation state.',
        );

        $clone->clearError();

        self::assertSame(
            ['other' => ['Replacement error.']],
            $source->getErrors(),
            'Clearing all errors must stay local.',
        );
        self::assertFalse(
            $clone->isValidated('name'),
            'Clearing all errors must remove validation state.',
        );
    }

    public function testCloneReplacingErrorsAsFirstWriteKeepsSourceErrors(): void
    {
        $source = new class extends BaseFormModel {};

        $source->addError('email', 'Source error.');

        $clone = clone $source;

        $clone->setErrors(['name' => ['Clone error.']]);

        self::assertSame(
            ['email' => ['Source error.']],
            $source->getErrors(),
            'Source messages must be unchanged.',
        );
        self::assertSame(
            ['name' => ['Clone error.']],
            $clone->getErrors(),
            'Clone messages must be replaced.',
        );
    }

    public function testCloneWithCustomHookKeepsErrorStateIndependent(): void
    {
        $source = new class extends BaseFormModel {
            public int $generation = 0;

            public function __clone(): void
            {
                $this->generation++;
            }
        };

        $source->addError('email', 'Original error.');

        $clone = clone $source;
        $secondClone = clone $clone;

        $clone->addError('email', 'Clone error.');
        $source->clearError();
        $secondClone->setErrors(['name' => ['Second clone error.']]);

        self::assertSame(
            1,
            $clone->generation,
            'The consumer clone hook must run.',
        );
        self::assertSame(
            2,
            $secondClone->generation,
            'Repeated cloning must preserve the consumer hook.',
        );
        self::assertSame(
            [],
            $source->getErrors(),
            'Source clearing must stay local.',
        );
        self::assertSame(
            ['Original error.', 'Clone error.'],
            $clone->getError('email'),
            'Clone messages must be isolated.',
        );
        self::assertSame(
            ['name' => ['Second clone error.']],
            $secondClone->getErrors(),
            'Replacement must stay local.',
        );
    }
}
