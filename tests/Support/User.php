<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests\Support;

use UIAwesome\FormModel\AbstractFormModel;

final class User extends AbstractFormModel
{
    public string $name = '';
    public Profile $profile;

    public function __construct(private readonly object|null $object = null)
    {
        $this->profile = new Profile($object);
    }

    /**
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return [
            'name' => $this->name,
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

    /**
     * @phpstan-return array<string, array<int, mixed>>
     */
    public function getRules(): array
    {
        return [
            'name' => [$this->object],
        ];
    }

    /**
     * @phpstan-return array<string, array<string, array<int, string>>>
     */
    public function getFieldConfigByProperties(): array
    {
        return [
            'name' => [
                'class()' => ['text-gray-100 dark:text-gray-100'],
            ],
        ];
    }
}
