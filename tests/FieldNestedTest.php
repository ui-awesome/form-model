<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests;

use stdClass;
use UIAwesome\FormModel\Tests\Support\User;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class FieldNestedTest extends \PHPUnit\Framework\TestCase
{
    public function testGetHintByPropertyNestedsSeveralNestedLevels(): void
    {
        $fieldModel = new User();

        $this->assertSame('Enter your name', $fieldModel->getHintByProperty('name'));
        $this->assertSame('Enter your bio', $fieldModel->getHintByProperty('profile.bio'));
        $this->assertSame('Enter street name', $fieldModel->getHintByProperty('profile.address.street'));
        $this->assertSame('Enter city name', $fieldModel->getHintByProperty('profile.address.city'));
        $this->assertSame('Enter country name', $fieldModel->getHintByProperty('profile.address.country.name'));
    }

    public function testGetLabelByPropertyNestedsSeveralNestedLevels(): void
    {
        $fieldModel = new User();

        $this->assertSame('Name', $fieldModel->getLabelByProperty('name'));
        $this->assertSame('Bio', $fieldModel->getLabelByProperty('profile.bio'));
        $this->assertSame('Street', $fieldModel->getLabelByProperty('profile.address.street'));
        $this->assertSame('City', $fieldModel->getLabelByProperty('profile.address.city'));
        $this->assertSame('Country', $fieldModel->getLabelByProperty('profile.address.country.name'));
    }

    public function testGetPlaceholderByPropertyNestedsSeveralNestedLevels(): void
    {
        $fieldModel = new User();

        $this->assertSame('Enter your name', $fieldModel->getPlaceholderByProperty('name'));
        $this->assertSame('Enter your bio', $fieldModel->getPlaceholderByProperty('profile.bio'));
        $this->assertSame('Enter street name', $fieldModel->getPlaceholderByProperty('profile.address.street'));
        $this->assertSame('Enter city name', $fieldModel->getPlaceholderByProperty('profile.address.city'));
        $this->assertSame('Enter country name', $fieldModel->getPlaceholderByProperty('profile.address.country.name'));
    }

    public function testGetRulesByPropertysSeveralNestedLevels(): void
    {
        $validatorObject = new stdClass();
        $fieldModel = new User($validatorObject);

        $this->assertSame([$validatorObject], $fieldModel->getRulesByProperty('name'));
        $this->assertNull($fieldModel->getRulesByProperty('profile.address.city'));
        $this->assertSame([$validatorObject], $fieldModel->getRulesByProperty('profile.bio'));
        $this->assertSame([$validatorObject], $fieldModel->getRulesByProperty('profile.address.street'));
    }

    public function testGetWidgetByPropertySeveralNestedLevels(): void
    {
        $fieldModel = new User();

        $this->assertSame(
            [
                'class()' => ['text-gray-100 dark:text-gray-100'],
            ],
            $fieldModel->getWidgetConfigByProperty('name')
        );
        $this->assertSame(
            [
                'class()' => ['text-green-100 dark:text-green-100'],
            ],
            $fieldModel->getWidgetConfigByProperty('profile.bio')
        );
        $this->assertSame(
            [
                'class()' => ['text-blue-100 dark:text-blue-100'],
            ],
            $fieldModel->getWidgetConfigByProperty('profile.address.street')
        );
        $this->assertSame(
            [
                'class()' => ['text-red-100 dark:text-red-100'],
            ],
            $fieldModel->getWidgetConfigByProperty('profile.address.city')
        );
    }
}
