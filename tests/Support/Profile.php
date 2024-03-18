<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests\Support;

use UIAwesome\FormModel\AbstractFormModel;

final class Profile extends AbstractFormModel
{
    public string $bio = '';
    public Address $address;

    public function __construct(private readonly object|null $object = null)
    {
        $this->address = new Address($object);
    }

    public function __debugInfo()
    {
        return [
            'address' => $this->address,
            'bio' => $this->bio,
        ];
    }

    public function getHints(): array
    {
        return [
            'bio' => 'Enter your bio',
        ];
    }

    public function getLabels(): array
    {
        return [
            'bio' => 'Bio',
        ];
    }

    public function getPlaceholders(): array
    {
        return [
            'bio' => 'Enter your bio',
        ];
    }

    public function getRules(): array
    {
        return [
            'bio' => [$this->object],
        ];
    }

    public function getWidgetConfigByProperties(): array
    {
        return [
            'bio' => [
                'class()' => ['text-green-100 dark:text-green-100'],
            ],
        ];
    }
}
