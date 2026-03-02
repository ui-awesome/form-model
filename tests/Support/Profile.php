<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests\Support;

use UIAwesome\FormModel\AbstractFormModel;

final class Profile extends AbstractFormModel
{
    public Address $address;
    public string $bio = '';

    public function __construct(private readonly object|null $object = null)
    {
        $this->address = new Address($object);
    }

    /**
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return [
            'address' => $this->address,
            'bio' => $this->bio,
        ];
    }

    /**
     * @phpstan-return array<string, array<string, array<int, string>>>
     */
    public function getFieldConfigs(): array
    {
        return [
            'bio' => [
                'class()' => ['text-green-100 dark:text-green-100'],
            ],
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

    /**
     * @phpstan-return array<string, array<int, mixed>>
     */
    public function getRules(): array
    {
        return [
            'bio' => [$this->object],
        ];
    }
}
