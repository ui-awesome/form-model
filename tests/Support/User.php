<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests\Support;

use stdClass;
use UIAwesome\FormModel\AbstractFormModel;

final class User extends AbstractFormModel
{
    public string $name;
    public Profile $profile;

    public function __construct(private readonly object|null $object = null)
    {
        $this->profile = new Profile($object);
    }

    public function __debugInfo()
    {
        return [
            'profile' => $this->profile,
        ];
    }

    public function getHints(): array
    {
        return [
            'name' => 'Enter your name',
        ];
    }

    public function getLabels(): array
    {
        return [
            'name' => 'Name',
        ];
    }

    public function getPlaceholders(): array
    {
        return [
            'name' => 'Enter your name',
        ];
    }

    public function getRules(): array
    {
        return [
            'name' => [$this->object],
        ];
    }

    public function getWidgetConfig(): array
    {
        return [
            stdClass::class => [
                'class()' => ['text-gray-100 dark:text-gray-100'],
            ],
        ];
    }

    public function getWidgetConfigByProperties(): array
    {
        return [
            'name' => [
                'class()' => ['text-gray-100 dark:text-gray-100'],
            ],
        ];
    }
}
