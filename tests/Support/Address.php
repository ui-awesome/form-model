<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests\Support;

use UIAwesome\FormModel\AbstractFormModel;

final class Address extends AbstractFormModel
{
    public string $city = '';
    public Country $country;
    public string $street = '';

    public function __construct(private readonly object|null $object = null)
    {
        $this->country = new Country($object);
    }

    /**
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return [
            'street' => $this->street,
            'city' => $this->city,
            'country' => $this->country,
        ];
    }

    public function getHints(): array
    {
        return [
            'street' => 'Enter street name',
            'city' => 'Enter city name',
        ];
    }

    public function getLabels(): array
    {
        return [
            'street' => 'Street',
            'city' => 'City',
        ];
    }

    public function getPlaceholders(): array
    {
        return [
            'street' => 'Enter street name',
            'city' => 'Enter city name',
        ];
    }

    /**
     * @phpstan-return array<string, array<int, mixed>>
     */
    public function getRules(): array
    {
        return [
            'street' => [$this->object],
        ];
    }

    /**
     * @phpstan-return array<string, array<string, array<int, string>>>
     */
    public function getFieldConfigByProperties(): array
    {
        return [
            'street' => [
                'class()' => ['text-blue-100 dark:text-blue-100'],
            ],
            'city' => [
                'class()' => ['text-red-100 dark:text-red-100'],
            ],
        ];
    }
}
