<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests\Support;

use UIAwesome\FormModel\AbstractFormModel;

final class Country extends AbstractFormModel
{
    public string $name = '';
    private string $postalCode = '';

    public function __construct(private readonly object|null $object = null) {}

    public function __debugInfo()
    {
        return [
            'name' => $this->name,
        ];
    }

    public function getHints(): array
    {
        return [
            'name' => 'Enter country name',
        ];
    }

    public function getLabels(): array
    {
        return [
            'name' => 'Country',
        ];
    }

    public function getPlaceholders(): array
    {
        return [
            'name' => 'Enter country name',
        ];
    }

    public function getRules(): array
    {
        return [
            'name' => [$this->object],
        ];
    }

    public function getWidgetConfigByProperties(): array
    {
        return [
            'name' => [
                'class()' => ['text-yellow-100 dark:text-yellow-100'],
            ],
        ];
    }
}
