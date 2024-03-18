<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests;

use stdClass;
use UIAwesome\FormModel\AbstractFormModel;
use UIAwesome\FormModel\Tests\Support\Country;
use UIAwesome\FormModel\Tests\Support\InputWidget;
use UIAwesome\FormModel\Tests\Support\User;
use UIAwesome\Html\Interop\InputInterface;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class FormModelTest extends \PHPUnit\Framework\TestCase
{
    public function testAddPropertyError(): void
    {
        $formModel = new Country();

        $formModel->addPropertyError('name', 'Name is required.');

        $this->assertSame(['Name is required.'], $formModel->getPropertyError('name'));
    }

    public function testApplyToHtmlRulesByProperty(): void
    {
        $formModel = new Country();

        $this->assertInstanceOf(
            InputInterface::class,
            $formModel->applyToHtmlRulesByProperty(new InputWidget(), 'username'),
        );
    }

    public function testGenerateLabel(): void
    {
        $formModel = new Country();

        $this->assertSame('Country', $formModel->getLabelByProperty('name'));
    }

    public function testGetErrors(): void
    {
        $formModel = new Country();

        $formModel->addPropertyError('name', 'Name is required.');

        $this->assertSame(['name' => ['Name is required.']], $formModel->getErrors());
    }

    public function testGetErrorsWithEmpty(): void
    {
        $formModel = new class () extends AbstractFormModel {};

        $this->assertSame([], $formModel->getErrors());
    }

    public function testGetErrorsWithFirstTrue(): void
    {
        $formModel = new Country();

        $formModel->setErrors(
            [
                'name' => ['The field is required', 'Invalid name'],
                'postalCode' => ['The field is required', 'Invalid postal code'],
            ],
        );

        $this->assertSame(
            [
                'name' => 'The field is required',
                'postalCode' => 'The field is required',
            ],
            $formModel->getErrors(first: true)
        );
    }

    public function testGetErrorSummary(): void
    {
        $formModel = new Country();

        $formModel->addPropertyError('name', 'Name is required.');
        $formModel->addPropertyError('postalCode', 'Postal code is required.');

        $this->assertSame(
            [
                'Name is required.',
                'Postal code is required.',
            ],
            $formModel->getErrorSummary()
        );
    }

    public function testGetErrorSummaryWithEmpty(): void
    {
        $formModel = new class () extends AbstractFormModel {};

        $this->assertSame([], $formModel->getErrorSummary());
    }

    public function testGetErrorSummaryWithFirstTrue(): void
    {
        $formModel = new Country();

        $formModel->setErrors(
            [
                'name' => ['The field is required', 'Invalid name'],
                'postalCode' => ['Invalid postal code', 'The field is required'],
            ],
        );

        $this->assertSame(
            [
                'name' => 'The field is required',
                'postalCode' => 'Invalid postal code',
            ],
            $formModel->getErrorSummary(first: true)
        );
    }

    public function testGetHint(): void
    {
        $formModel = new Country();

        $this->assertSame('Enter country name', $formModel->getHintByProperty('name'));
    }

    public function testGetHints(): void
    {
        $formModel = new Country();

        $this->assertSame(['name' => 'Enter country name'], $formModel->getHints());
    }

    public function testGetHintsWithEmpty(): void
    {
        $formModel = new class () extends AbstractFormModel {};

        $this->assertSame([], $formModel->getHints());
    }

    public function testGetLabel(): void
    {
        $formModel = new Country();

        $this->assertSame('Country', $formModel->getLabelByProperty('name'));
    }

    public function testGetLabels(): void
    {
        $formModel = new Country();

        $this->assertSame(['name' => 'Country'], $formModel->getLabels());
    }

    public function testGetLabelsWithEmpty(): void
    {
        $formModel = new class () extends AbstractFormModel {};

        $this->assertSame([], $formModel->getLabels());
    }

    public function testGetPlaceholder(): void
    {
        $formModel = new Country();

        $this->assertSame('Enter country name', $formModel->getPlaceholderByProperty('name'));
    }

    public function testGetPlaceholders(): void
    {
        $formModel = new Country();

        $this->assertSame(['name' => 'Enter country name'], $formModel->getPlaceholders());
    }

    public function testGetPlaceholdersWithEmpty(): void
    {
        $formModel = new class () extends AbstractFormModel {};

        $this->assertSame([], $formModel->getPlaceholders());
    }

    public function testGetPropetyError(): void
    {
        $formModel = new Country();

        $this->assertEmpty($formModel->getPropertyError('name'));
    }

    public function testGetPropertyErrorWithFirstTrue(): void
    {
        $formModel = new Country();

        $this->assertSame('', $formModel->getPropertyError('name', true));
    }

    public function testGetRules(): void
    {
        $formModel = new Country();

        $this->assertEmpty($formModel->getRules());
    }

    public function testGetWidgetConfig(): void
    {
        $formModel = new User();

        $this->assertSame(
            [
                stdClass::class => [
                    'class()' => ['text-gray-100 dark:text-gray-100'],
                ],
            ],
            $formModel->getWidgetConfig()
        );
    }

    public function testGetWidgetConfigWithEmpty(): void
    {
        $formModel = new Country();

        $this->assertEmpty($formModel->getWidgetConfig());
    }

    public function testGetWidgetConfigByClass(): void
    {
        $formModel = new User();

        $this->assertSame(
            [
                'class()' => ['text-gray-100 dark:text-gray-100'],
            ],
            $formModel->getWidgetConfigByClass(stdClass::class)
        );
    }

    public function testGetWidgetConfigByProperty(): void
    {
        $formModel = new User();

        $this->assertSame(
            [
                'class()' => ['text-gray-100 dark:text-gray-100'],
            ],
            $formModel->getWidgetConfigByProperty('name')
        );
    }

    public function testGetWidgetConfigByPropertyWithEmpty(): void
    {
        $formModel = new Country();

        $this->assertEmpty($formModel->getWidgetConfigByProperty('name'));
    }

    public function testHasPropertyError(): void
    {
        $formModel = new Country();

        $this->assertFalse($formModel->hasPropertyError('name'));

        $formModel->addPropertyError('name', 'Name is required.');

        $this->assertTrue($formModel->hasPropertyError('name'));
    }

    public function testHasPropertyValidate(): void
    {
        $formModel = new Country();

        $this->assertFalse($formModel->hasPropertyValidate('name'));

        $formModel->clearError('name');

        $this->assertTrue($formModel->hasPropertyValidate('name'));
    }

    public function testSetErrors(): void
    {
        $formModel = new Country();

        $formModel->setErrors(['name' => ['Name is required.']]);

        $this->assertSame(['Name is required.'], $formModel->getPropertyError('name'));
    }
}
